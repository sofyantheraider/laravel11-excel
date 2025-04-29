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
    public $userMap = [];

    public function sheets(): array
    {
        return [
            'Users'  => new class($this) implements ToCollection, WithHeadingRow {
                protected $parent;

                public function __construct($parent)
                {
                    $this->parent = $parent;
                }

                public function collection(Collection $rows)
                {

                    foreach ($rows as $row) {
                        $user = User::create([
                            'name'     => $row['name'],
                            'email'    => $row['email'],
                            'password' => bcrypt($row['password']),
                        ]);

                        // Simpan mapping email => user_id
                        $this->parent->userMap[$row['soal']] = $user->id;
                    }
                }
            },

            'Orders' => new class($this) implements ToCollection, WithHeadingRow {
                protected $parent;

                public function __construct($parent)
                {
                    $this->parent = $parent;
                }

                public function collection(Collection $rows)
                {
                    foreach ($rows as $row) {
                        $soalOrder = $row['soal_order'];
                        $userId = $this->parent->userMap[$soalOrder] ?? null;

                        if ($userId) {
                            Order::create([
                                'order_number' => $row['order_number'],
                                'total'        => $row['total'],
                                'user_id'      => $userId,
                            ]);
                        }
                    }
                }
            },
        ];
    }
}
