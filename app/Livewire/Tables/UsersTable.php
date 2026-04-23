<?php

namespace App\Livewire\Tables;

use App\Service\DatatableService;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UsersTable extends DataTableComponent
{
    protected $model = User::class;

    public function configure(): void
    {
        DatatableService::setConfigures($this);
        $this->setSearchIcon('heroicon-m-magnifying-glass');
    }

    protected $listeners = ['refreshTable' => '$refresh'];

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("نام", "name")
                ->sortable()
                ->searchable(),
            Column::make("شماره همراه", "mobile")
                ->sortable()
                ->searchable(),
            Column::make("نقش")
                ->sortable()
                ->searchable()
                ->label(function ($model) {
                    return $model->rolesNames;
                }),
            Column::make('وضعیت', "status")
                ->format(function ($value, $row) {
                    return view('components.table.toggle', [
                        'checked' => (bool)$value,
                        'id' => $row->id,
                    ]);
                })
                ->html()
                ->sortable(),
            Column::make('عملیات')
                ->label(fn($row) => view('components.table.actions', [
                    'row' => $row,
                    'buttons' => [
                        'edit',
                    ],
                    'route' => route('users.edit', $row->id),
                ]))
                ->html(),
        ];
    }

    public function filters(): array
    {
        return [
            MultiSelectFilter::make('نقش')
                ->options(Role::pluck('name', 'id')->toArray())
                ->filter(function ($query, $value) {
                    if (!empty($value)) {
                        $role_names = Role::whereIn('id', $value)->pluck('name')->toArray();
                        $query->role($role_names);
                    }
                }),
            SelectFilter::make('وضعیت')
                ->options([
                    '' => 'همه',
                    true => 'فعال',
                    false => 'غیرفعال',
                ])
                ->filter(function ($query, $value) {
                    if (!empty($value)) {
                        $query->where('status', $value);
                    }
                }),
        ];
    }

    public function exportExcel(): BinaryFileResponse
    {
        $query = $this->builder();

        if ($this->getSearch()) {
            $search = $this->getSearch();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $data = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'نام' => $item->name,
                'شماره همراه' => $item->mobile,
                'نقش' => $item->rolesNames,
            ];
        });

        return Excel::download(new class($data) implements FromCollection, WithHeadings {
            protected $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function collection()
            {
                return $this->data;
            }

            public function headings(): array
            {
                return array_keys($this->data[0]);
            }
        }, 'مقادیر اولیه - مدیریت کاربران.xlsx');
    }
}
