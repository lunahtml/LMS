<?php
//backend/database/seeders/RoleSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

// class RoleSeeder extends Seeder
// {
//     public function run(): void
//     {
//         // Создание ролей, если их нет
//         $roles = ['admin', 'company', 'student', 'teacher'];
//         foreach ($roles as $roleName) {
//             Role::firstOrCreate(['name' => $roleName]);
//         }

//         // Назначаем администратору роль admin
//         $admin = User::where('email', 'admin@lms.test')->first();
//         if ($admin && !$admin->hasRole('admin')) {
//             $admin->assignRole('admin');
//         }
//     }
// }
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Создание ролей
        $roles = ['admin', 'company', 'student', 'teacher'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Создаем админа
        $admin = User::firstOrCreate(
            ['email' => 'admin@lms.test'],
            [
                'name' => 'Admin',
                'password' => bcrypt('123'),
            ]
        );
        $admin->assignRole('admin');

        // Создаем компанию
        $company = User::firstOrCreate(
            ['email' => 'company@lms.test'],
            [
                'name' => 'Company 1',
                'password' => bcrypt('123'),
                'company_id' => 1
            ]
        );
        $company->assignRole('company');

        // Создаем студента
        $student = User::firstOrCreate(
            ['email' => 'student@lms.test'],
            [
                'name' => 'Student 1',
                'password' => bcrypt('123'),
                'company_id' => 1
            ]
        );
        $student->assignRole('student');

        // Создаем учителя
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@lms.test'],
            [
                'name' => 'Teacher 1',
                'password' => bcrypt('123'),
                'company_id' => 1
            ]
        );
        $teacher->assignRole('teacher');
    }
}