<?php

use Illuminate\Database\Seeder;

use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::query()->truncate();
        Permission::create(['permission_id' => '1',   'name' => 'accessToMembersModule',       'level' => '1', 'parent_id' => null, 'title' => 'Members Module' ]);
        Permission::create(['permission_id' => '2',   'name' => 'accessToSharesModule',        'level' => '0', 'parent_id' => null, 'title' => 'Shares Module' ]);
        Permission::create(['permission_id' => '3',   'name' => 'accessToSavingsModule',       'level' => '0', 'parent_id' => null, 'title' => 'Savings Module' ]);
        Permission::create(['permission_id' => '4',   'name' => 'accessToLoansModule',         'level' => '0', 'parent_id' => null, 'title' => 'Loans Module' ]);
        Permission::create(['permission_id' => '5',   'name' => 'accessToAssessmentModule',    'level' => '0', 'parent_id' => null, 'title' => 'Assessment Module' ]);
        Permission::create(['permission_id' => '6',   'name' => 'accessToTransactionsModule',  'level' => '0', 'parent_id' => null, 'title' => 'Transactions Module' ]);
        Permission::create(['permission_id' => '7',   'name' => 'accessToAnnouncementsModule', 'level' => '0', 'parent_id' => null, 'title' => 'Announcements Module' ]);
        Permission::create(['permission_id' => '8',   'name' => 'accessToMemberSharesModule',  'level' => '0', 'parent_id' => null, 'title' => 'Member Shares Module' ]);
        Permission::create(['permission_id' => '9',   'name' => 'accessToMemberSavingsModule', 'level' => '0', 'parent_id' => null, 'title' => 'Member Savings Module' ]);
        Permission::create(['permission_id' => '10',  'name' => 'accessToMemberLoansModule',   'level' => '0', 'parent_id' => null, 'title' => 'Member Loans Module' ]);
        Permission::create(['permission_id' => '11',  'name' => 'accessToMemberProfileModule', 'level' => '0', 'parent_id' => null, 'title' => 'Member Profile Module' ]);
        Permission::create(['permission_id' => '12',  'name' => 'accessToDashboardWidgets',    'level' => '0', 'parent_id' => null, 'title' => 'Access To Dashboard Widgets' ]);
    }
}