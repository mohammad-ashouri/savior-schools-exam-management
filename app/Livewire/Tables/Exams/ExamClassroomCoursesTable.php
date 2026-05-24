<?php

namespace App\Livewire\Tables\Exams;

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

class ExamClassroomCoursesTable extends DataTableComponent
{
    protected $model = ClassroomCourse::class;

    public $classroom_id;

    public $student;

    public function configure(): void
    {
        DatatableService::setConfigures($this);
        $this->setSearchIcon('heroicon-m-magnifying-glass');
        $this->perPage = 50;
        $this->setRefreshTime(10000);
    }

    protected $listeners = ['refreshTable' => '$refresh'];

    public function mount($classroom_id, $student): void
    {
        $this->classroom_id = $classroom_id;
        $this->student = $student;
    }

    public function builder(): Builder
    {
        return ClassroomCourse::query()
            ->with(['classroomInfo', 'courseInfo', 'teacherInfo'])
            ->where('classroom_id', $this->classroom_id)
            ->orderBy('courseInfo.name');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Academic Year", "classroomInfo.academicYearInfo.name")
                ->searchable()
                ->sortable(),
            Column::make("Course", "courseInfo.name")
                ->searchable()
                ->sortable(),
            Column::make("Grade", "classroomInfo.gradeInfo.name")
                ->searchable()
                ->sortable(),
            Column::make("Teacher", "teacher_id")
                ->format(function ($query, $field) {
                    return User::find($query)->english_fullname;
                })
                ->searchable()
                ->sortable(),
            Column::make("First Term")
                ->label(function ($row) {
                    $full_exam_info = ExamService::getFullExamInfo($row->id);
                    if (isset($full_exam_info['first'])) {
                        return $full_exam_info['first']['date'] . " " . $full_exam_info['first']['time'] . " | " . $full_exam_info['first']['duration'] . " Minutes";
                    }
                    return null;
                })
                ->searchable()
                ->sortable(),
            Column::make("Second Term")
                ->label(function ($row) {
                    $full_exam_info = ExamService::getFullExamInfo($row->id);
                    if (isset($full_exam_info['second'])) {
                        return $full_exam_info['second']['date'] . " " . $full_exam_info['second']['time'] . " | " . $full_exam_info['second']['duration'] . " Minutes";
                    }
                    return null;
                })
                ->searchable()
                ->sortable(),
            Column::make("Retake")
                ->label(function ($row) {
                    $full_exam_info = ExamService::getFullExamInfo($row->id);
                    if (isset($full_exam_info['retake'])) {
                        return $full_exam_info['retake']['date'] . " " . $full_exam_info['retake']['time'] . " | " . $full_exam_info['retake']['duration'] . " Minutes";
                    }
                    return null;
                })
                ->searchable()
                ->sortable(),
            Column::make('Operations')
                ->label(function ($row) {
                    $data = ['row' => $row, 'buttons' => null];
                    $show_button = ExamService::checkExamStatus($row->id);

                    if ($show_button!=null) {
                        $data['buttons'] = [
                            'start_exam',
                        ];
                        $data['exam_route_values'] = [
                            'classroom_course_id' => $row->id,
                        ];
//                        $data['exam_button_label'] = $label;
                    }

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
