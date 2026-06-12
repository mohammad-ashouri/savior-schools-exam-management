<?php

namespace App\Livewire\Tables;

use App\Livewire\Management\Questions;
use App\Models\Management\Classroom;
use App\Models\Management\ClassroomCourse;
use App\Models\Management\Course;
use App\Models\Management\ExamInfo;
use App\Models\Management\Option;
use App\Models\Management\Question;
use App\Service\DatatableService;
use App\Service\ExamService;
use App\Service\TextService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
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

class ClassroomCourseQuestionsTable extends DataTableComponent
{
    public $classroom_course_id;

    public $term;

    protected $model = Question::class;

    public function configure(): void
    {
        DatatableService::setConfigures($this);
        $this->setSearchIcon('heroicon-m-magnifying-glass');
    }

    protected $listeners = ['refreshTable' => '$refresh'];

    public function mount($classroom_course_id, $term): void
    {
        $this->classroom_course_id = $classroom_course_id;
        $this->term = $term;
    }

    public function builder(): Builder
    {
        return Question::query()
            ->where('classroom_course_id', $this->classroom_course_id)
            ->where('term', $this->term)
            ->with(['classroomCourseInfo'])
            ->orderBy('created_at', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->searchable()
                ->sortable(),
            Column::make("Type", "question_type")
                ->format(function ($value) {
                    return !is_null($value) ? TextService::getQuestionTypeTitle($value) : null;
                })
                ->searchable()
                ->sortable(),
            Column::make("Title", "title")
                ->format(function ($query) {
                    return view('components.management.questions.title-html-decode', ["data" => $query]);
                })
                ->searchable()
                ->sortable(),
            Column::make("Options (For multiple choice questions)")
                ->label(function ($query) {
                    if ($query->question_type == "multiple_choice") {
                        return view('components.management.questions.options-html-decode', ['options' => $query->options->sortBy('id')->pluck('option')->toArray()]);
                    }
                    return null;
                })
                ->searchable()
                ->sortable(),
            Column::make("Correct Answer (For multiple choice questions)")
                ->label(function ($query) {
                    if ($query->question_type == "multiple_choice") {
                        return view('components.management.questions.options-html-decode', ['options' => [Option::where('question_id', $query->id)->where('correct', true)->first()?->option] ?? []]);
                    }
                    return null;
                })
                ->searchable()
                ->sortable(),
            Column::make('Operations')
                ->label(function ($row) {
                    $row_model = $this->model::where('id', $row->id)->first();
                    $started = ExamService::checkExamStarted($row_model->classroom_course_id, $row_model->term);
                    $data = [
                        'row' => $row,
                        'route' => route('management.courses.questions.edit', $row->id),
                        'buttons' => ['edit'],
                    ];

                    if (!$started) {
                        $data['buttons'][] = 'delete';
                    }

                    if ($row_model->question_type == "multipart_question") {
                        $data['buttons'][] = 'sub questions';
                        $data['sub_questions_route_name'] =
                            route('management.courses.questions.sub-questions', [
                                'question_id' => $row_model->id,
                            ]);
                    }
                    return view('components.table.actions', $data);
                })
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
        }, '1.xlsx');
    }
}
