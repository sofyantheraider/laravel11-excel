<?php
//TODO Relasi data melalui dari database
namespace App\Imports;

use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiSheetImport implements WithMultipleSheets
{
    /**
    * @param Collection $collection
    */
    public function sheets(): array
    {
        return [
            'Users'  => new class implements ToCollection, WithHeadingRow {
                public function collection(Collection $rows)
                {
                    foreach ($rows as $row) {
                        User::create([
                            'name'     => $row['name'],
                            'email'    => $row['email'],
                            'password' => bcrypt($row['password']),
                        ]);
                    }
                }
            },

            'Orders' => new class implements ToCollection, WithHeadingRow {
                public function collection(Collection $rows)
                {
                    foreach ($rows as $row) {
                        $user = User::where('email', $row['user_email'])->first(); // relasi via email

                        if ($user) {
                            Order::create([
                                'order_number' => $row['order_number'],
                                'total'        => $row['total'],
                                'user_id'      => $user->id,
                            ]);
                        }
                    }
                }
            },
        ];
    }
}
