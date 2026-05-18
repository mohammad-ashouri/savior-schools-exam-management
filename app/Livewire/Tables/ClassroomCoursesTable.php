<?php

namespace App\Livewire\Tables;

use App\Models\Management\Classroom;
use App\Models\Management\ClassroomCourse;
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

class ClassroomCoursesTable extends DataTableComponent
{
    protected $classroom_id;

    protected $model = ClassroomCourse::class;

    public function configure(): void
    {
        DatatableService::setConfigures($this);
        $this->setSearchIcon('heroicon-m-magnifying-glass');
        $this->perPage = 25;
    }

    protected $listeners = ['refreshTable' => '$refresh'];

    public function mount($classroom_id): void
    {
        $this->classroom_id = $classroom_id;
    }

    public function builder(): Builder
    {
        return ClassroomCourse::query()
            ->where('classroom_id', $this->classroom_id)
            ->with(['classroomInfo', 'courseInfo', 'teacherInfo'])
            ->orderBy('courseInfo.name');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Course", "courseInfo.name")
                ->searchable()
                ->sortable(),
            Column::make("Teacher", "teacher_id")
                ->format(function ($query, $field) {
                    return User::find($query)->english_fullname;
                })
                ->searchable()
                ->sortable(),
            Column::make('Operations')
                ->label(fn($row) => view('components.table.actions', [
                    'row' => $row,
                    'buttons' => [
                        'first_term_exam',
                        'second_term_exam',
                        'retake_exam',
                    ],
                    'first_term_exam_route_name' => route('management.courses.questions.index', ['classroom_id' => $this->classroom_id, 'classroom_course_id' => $row->id, 'term' => 'first']),
                    'second_term_exam_route_name' => route('management.courses.questions.index', ['classroom_id' => $this->classroom_id, 'classroom_course_id' => $row->id, 'term' => "second"]),
                    'retake_exam_route_name' => route('management.courses.questions.index', ['classroom_id' => $this->classroom_id, 'classroom_course_id' => $row->id, 'term' => "retake"]),
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
