<?php

namespace App\Livewire\Tables;

use App\Models\Management\Classroom;
use App\Models\Management\Course;
use App\Service\DatatableService;
use Illuminate\Database\Eloquent\Builder;
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

class ClassroomsTable extends DataTableComponent
{
    protected $model = Classroom::class;

    public function configure(): void
    {
        DatatableService::setConfigures($this);
        $this->setSearchIcon('heroicon-m-magnifying-glass');
    }

    protected $listeners = ['refreshTable' => '$refresh'];

    public function builder(): Builder
    {
        return Classroom::query()
            ->with(['courses'])
            ->orderByDesc('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Academic Year", "academicYearInfo.name")
                ->searchable()
                ->sortable(),
            Column::make("Name", "name")
                ->searchable()
                ->sortable(),
            Column::make("Courses")
                ->label(function ($row) {
                    $classroom_courses = $row->courses
                        ->map(function ($query) {
                            return $query->courseInfo->name . " (" . $query->teacherInfo->english_fullname . ")";
                        });
                    return $classroom_courses->implode(', ');
                })
                ->searchable()
                ->sortable(),
            Column::make('Operations')
                ->label(fn($row) => view('components.table.actions', [
                    'row' => $row,
                    'buttons' => [
                        'courses',
                    ],
                    'courses_route_name' => route('management.courses.index', ['classroom_id' => $row->id]),
                ]))
                ->html(),
        ];
    }

    public function filters(): array
    {
        return [
//            MultiSelectFilter::make('نقش')
//                ->options(Role::pluck('name', 'id')->toArray())
//                ->filter(function ($query, $value) {
//                    if (!empty($value)) {
//                        $role_names = Role::whereIn('id', $value)->pluck('name')->toArray();
//                        $query->role($role_names);
//                    }
//                }),
//            SelectFilter::make('وضعیت')
//                ->options([
//                    '' => 'همه',
//                    true => 'فعال',
//                    false => 'غیرفعال',
//                ])
//                ->filter(function ($query, $value) {
//                    if (!empty($value)) {
//                        $query->where('status', $value);
//                    }
//                }),
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
