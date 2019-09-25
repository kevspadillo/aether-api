<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Models\MemberTransactions;
use App\Models\ShareTransactions;
use App\Models\SavingsTransactions;
use App\Models\LoanTransactions;
use JWTAuth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as PhpOfficeDate;

class ImportController extends Controller
{
    private $User;
    private $MemberTransactions;
    private $ShareTransactions;
    private $SavingsTransactions;
    private $LoanTransactions;

    /**
     * @param User                $User
     * @param MemberTransactions  $MemberTransactions
     */
    public function __construct(
        User                $User,
        MemberTransactions  $MemberTransactions,
        ShareTransactions   $ShareTransactions,
        SavingsTransactions $SavingsTransactions
    ) {
        $this->User                = $User;
        $this->MemberTransactions  = $MemberTransactions;
        $this->ShareTransactions   = $ShareTransactions;
        $this->SavingsTransactions = $SavingsTransactions;
    }

    public function store(Request $Request)
    {   

        if (!$Request->hasFile('file')) {
            return response()->json(['message' => 'No file uploaded'], 409);
        }

        $extension = $Request->file->extension();

        if (!in_array($extension, ['xlsx', 'xls'])) {
            return response()->json(['message' => 'Invalid file uploaded'], 409);
        }

        $filePath = $Request->file->path();

        $spreadsheet = IOFactory::load($filePath);

        $sheetsCount = $spreadsheet->getSheetCount();

        $records = [];

        for ($sheetCounter = 0; $sheetCounter < $sheetsCount; $sheetCounter++) { 
                
            $memberRecord = [];

            $sheet = $spreadsheet->getSheet($sheetCounter);

            $rowCounter = 9;

            $rowHasValue = true;

            $memberId = $sheet->getCell('N1')->getValue();

            while ($rowHasValue) {
                
                $dateValue = $sheet->getCell('A' . $rowCounter)->getValue();

                if (!empty($dateValue)) {
                 
                    $date = PhpOfficeDate::excelToDateTimeObject($dateValue);

                    $recordKey = $rowCounter . '|' . $date->format('Y-m-d');

                    $memberRecord[$recordKey] = [];

                    $shares = [];

                    $referenceId = null;

                    if (!empty($sheet->getCell('C' . $rowCounter)->getValue())) {
                        $referenceId = $sheet->getCell('C' . $rowCounter)->getValue();
                    }

                    $shares['reference_id']  = $referenceId;
                    $shares['share_capital'] = $sheet->getCell('D' . $rowCounter)->getCalculatedValue() ?? 0.0;
                    $shares['share']         = $sheet->getCell('N' . $rowCounter)->getCalculatedValue() ?? 0.0;

                    $savings = [];
                    $savings['reference_id']    = $referenceId;
                    $savings['savings_deposit'] = $sheet->getCell('E' . $rowCounter)->getCalculatedValue() ?? 0.0;
                    $savings['interest']        = $sheet->getCell('M' . $rowCounter)->getCalculatedValue() ?? 0.0;
                    $savings['savings']         = $sheet->getCell('O' . $rowCounter)->getCalculatedValue() ?? 0.0;

                    $loans = [];
                    $loans['reference_id']      = $referenceId;
                    $loans['loans_receivable']  = $sheet->getCell('F' . $rowCounter)->getCalculatedValue() ?? 0.0;
                    $loans['interest_on_loans'] = $sheet->getCell('G' . $rowCounter)->getCalculatedValue() ?? 0.0;
                    $loans['penalty']           = $sheet->getCell('H' . $rowCounter)->getCalculatedValue() ?? 0.0;
                    $loans['remaining_loan']    = $sheet->getCell('P' . $rowCounter)->getCalculatedValue() ?? 0.0;
                    $loans['total_interest']    = $sheet->getCell('R' . $rowCounter)->getCalculatedValue() ?? 0.0;
                    $loans['total_penalty']     = $sheet->getCell('S' . $rowCounter)->getCalculatedValue() ?? 0.0;

                    $memberRecord[$recordKey]['shares']  = $shares;
                    $memberRecord[$recordKey]['savings'] = $savings;
                    $memberRecord[$recordKey]['loans']   = $loans;

                }

                if (($date->format('m') == 12) && empty($dateValue)) {
                    $rowHasValue = false;
                }

                $rowCounter++;
            }

            $records[$memberId] = ["records" => $memberRecord];
        }


        $memberIds = array_keys($records);

        $members = $this->User->getMembers($memberIds);

        foreach ($members as $member) {
            $records[$member->user_id]['member_record'] = $member;
        }
        return response()->json($records);
    }

    public function saveTransaction(Request $Request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $data = $Request->all();

        // $MemberLoanTransactions = new MemberTransactions();
        // $MemberLoanTransactions->transaction_type = 'LOAN';
        // $MemberLoanTransactions->is_posted = 0;
        // $MemberLoanTransactions->user_id = $user->user_id;
        // $MemberLoanTransactions->save();

        // $loansTransactionId = $MemberLoanTransactions->member_transaction_id;

        $MemberShareTransactions = new MemberTransactions();
        $MemberShareTransactions->transaction_type = 'SHARE';
        $MemberShareTransactions->is_posted = 0;
        $MemberShareTransactions->user_id = $user->user_id;
        $MemberShareTransactions->save();

        $shareTransactionId = $MemberShareTransactions->member_transaction_id;

        $MemberSavingsTransactions = new MemberTransactions();
        $MemberSavingsTransactions->transaction_type = 'SAVINGS';
        $MemberSavingsTransactions->is_posted = 0;
        $MemberSavingsTransactions->user_id = $user->user_id;
        $MemberSavingsTransactions->save();

        $savingsTransactionId = $MemberSavingsTransactions->member_transaction_id;

        foreach ($data as $memberId => $transactionRecords) {
            
            $shares  = [];
            $savings = [];
            $loans   = [];

            foreach ($transactionRecords['records'] as $transactionDate => $transaction) {

                $date = explode('|', $transactionDate);

                $shares[$transactionDate] = [
                    'member_transaction_id' => $shareTransactionId,
                    'member_id'             => $memberId,
                    'transaction_date'      => $date[1],
                    'reference_id'          => $transaction['shares']['reference_id'],
                    'share_capital'         => $transaction['shares']['share_capital'],
                    'share'                 => $transaction['shares']['share'],
                ];

                $savings[$transactionDate] = [ 
                    'member_id'              => $memberId,
                    'member_transaction_id'  => $savingsTransactionId,
                    'reference_id'           => $transaction['savings']['reference_id'],
                    'transaction_date'       => $date[1],
                    'savings_deposit'        => $transaction['savings']['savings_deposit'],
                    'interest'               => $transaction['savings']['interest'],
                    'savings'                => $transaction['savings']['savings']
                ];

                // $loans[$transactionDate] = [
                //     'member_id'             => $memberId,
                //     'member_transaction_id' => $loansTransactionId,
                //     'reference_id'          => $transaction['loans']['reference_id'],
                //     'transaction_date'      => $date[1],
                //     'loans_receivable'      => $transaction['loans']['loans_receivable'],
                //     'interest_on_loan'      => $transaction['loans']['interest_on_loans'],
                //     'penalty'               => $transaction['loans']['penalty'],
                //     'remaining_loan'        => $transaction['loans']['remaining_loan'],
                //     'total_interest'        => $transaction['loans']['total_interest'],
                //     'total_penalty'         => $transaction['loans']['total_penalty'],
                // ];
            }
            
            $this->ShareTransactions->saveImportedShareTransactions($shares, $memberId);
            $this->SavingsTransactions->saveImportedSavingsTransactions($savings, $memberId);
            // SavingsTransactions::insert($savings);
            // LoanTransactions::insert($loans);
        }
        return response()->json(['message' => 'Transactions Saved.']);
    }
}
