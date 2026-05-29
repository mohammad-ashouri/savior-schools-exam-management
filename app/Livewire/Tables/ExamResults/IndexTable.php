<?php

namespace App\Livewire\Tables\ExamResults;

use App\Models\Exam\StudentExam;
use App\Models\Management\Classroom;
use App\Models\Management\ClassroomCourse;
use App\Models\Management\Course;
use App\Service\DatatableService;
use App\Service\ExamService;
use Carbon\Carbon;
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
use Rappasoft\LaravelLivewireTables\Traits\WithBulkActions;

class IndexTable extends DataTableComponent
{
    protected $model = StudentExam::class;

    public $classroom_course_id;

    public function configure(): void
    {
        DatatableService::setConfigures($this);
        $this->setSearchIcon('heroicon-m-magnifying-glass');
        $this->perPage = 50;
    }

    protected $listeners = ['refreshTable' => '$refresh'];

    public function mount($classroom_course_id): void
    {
        $this->classroom_course_id = $classroom_course_id;
    }

    public function builder(): Builder
    {
        return StudentExam::query()
            ->with(['classroomStudentInfo', 'classroomCourseInfo', 'questions'])
            ->where('classroom_course_id', $this->classroom_course_id);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Student", 'classroom_student_id')
                ->format(function ($query, $q) {
                    return $q->classroomStudentInfo->applianceInfo->studentGeneralInfo->en_fullname;
                })
                ->sortable(),
            Column::make("Started At", 'created_at')
                ->searchable()
                ->sortable(),
            Column::make("Finished At", 'finished_at')
                ->searchable()
                ->sortable(),
            Column::make('Operations')
                ->label(function ($row) {
                    $data = ['row' => $row];

                    $data['buttons'] = [
                        'show_exam_form',
                    ];
                    $data['exam_route_values'] = route('exam-result.show', ['student_exam_id' => $row->id]);

                    return view('components.table.actions', $data);
                }),
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
}
