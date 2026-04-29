<?php

namespace App\Service;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;
use Morilog\Jalali\Jalalian;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DatatableService
{
    public static function setConfigures($datatable_component): void
    {
        $datatable_component->setPrimaryKey('id');
        $datatable_component->setSearchPlaceholder('Search the entire table');
        $datatable_component->setSearchDebounce(1000);
        $datatable_component->setDefaultSort('created_at', 'desc');
    }

    public static function returnCatalogsColumnsJustName(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make('نام', "name")
                ->searchable()
                ->sortable(),
            Column::make('وضعیت', "status")
                ->format(function ($value, $row) {
                    return view('components.table.toggle', [
                        'checked' => (bool)$value,
                        'id' => $row->id,
                    ]);
                })
                ->html()
                ->sortable(),
            Column::make("ثبت کننده", "adderInfo.name")
                ->searchable()
                ->sortable(),
            Column::make("تاریخ ثبت", "created_at")
                ->format(function ($created_at) {
                    return Jalalian::fromCarbon($created_at)->format("H:i:s Y/m/d");
                })
                ->searchable()
                ->sortable(),
            Column::make("ویرایشگر", "editorInfo.name")
                ->sortable()
                ->searchable(),
            Column::make('تاریخ ویرایش', "updated_at")
                ->format(function ($date, $model) {
                    return $model['editorInfo.name'] != null ? Jalalian::forge($date)->format('H:i:s Y/m/d') : null;
                })
                ->sortable()
                ->searchable(),
            Column::make('عملیات')
                ->label(fn($row) => view('components.table.actions', [
                    'row' => $row,
                    'buttons' => [
                        'edit',
                    ],
                ]))
                ->html(),
        ];
    }

    public static function returnCatalogsColumnsWithFile(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make('نام', "name")
                ->searchable()
                ->sortable(),
            Column::make('فایل پیوست', "attachmentFile.src")
                ->label(function ($model) {
                    if (isset($model->attachmentFile->src)) {
                        return view('components.table.attachment_file', [
                            'src' => $model->attachmentFile->src,
                        ]);
                    }
                    return null;
                })
                ->html(),
            Column::make('وضعیت', "status")
                ->format(function ($value, $row) {
                    return view('components.table.toggle', [
                        'checked' => (bool)$value,
                        'id' => $row->id,
                    ]);
                })
                ->html()
                ->sortable(),
            Column::make("ثبت کننده", "adderInfo.name")
                ->searchable()
                ->sortable(),
            Column::make("تاریخ ثبت", "created_at")
                ->format(function ($created_at) {
                    return Jalalian::fromCarbon($created_at)->format("H:i:s Y/m/d");
                })
                ->searchable()
                ->sortable(),
            Column::make("ویرایشگر", "editorInfo.name")
                ->sortable()
                ->searchable(),
            Column::make('تاریخ ویرایش', "updated_at")
                ->format(function ($date, $model) {
                    return $model['editorInfo.name'] != null ? Jalalian::forge($date)->format('H:i:s Y/m/d') : null;
                })
                ->sortable()
                ->searchable(),
            Column::make('عملیات')
                ->label(fn($row) => view('components.table.actions', [
                    'row' => $row,
                    'buttons' => [
                        'edit',
                    ],
                ]))
                ->html(),
        ];
    }

    public static function returnCatalogsExcelExportJustName($datatable_component, $export_filename = 'export'): BinaryFileResponse
    {
        $query = $datatable_component->builder();

        if ($datatable_component->getSearch()) {
            $search = $datatable_component->getSearch();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $data = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'نام' => $item->name,
                'وضعیت' => $item->status ? 'فعال' : 'غیرفعال',
                'ثبت کننده' => $item->adderInfo->name,
                'تاریخ ثبت' => Jalalian::fromCarbon($item->created_at)->format("Y/m/d H:i:s"),
                'ویرایش کننده' => $item->editorInfo?->name,
                'تاریخ ویرایش' => Jalalian::fromCarbon($item->updated_at)->format("Y/m/d H:i:s"),
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
        }, $export_filename);
    }

    public static function returnCatalogsExcelExportWithFile($datatable_component, $export_filename = 'export'): BinaryFileResponse
    {
        $query = $datatable_component->builder();

        if ($datatable_component->getSearch()) {
            $search = $datatable_component->getSearch();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $data = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'نام' => $item->name,
                'فایل پیوست' => isset($item->attachmentFile->src) ? env('APP_URL') . $item->attachmentFile->src : '',
                'وضعیت' => $item->status ? 'فعال' : 'غیرفعال',
                'ثبت کننده' => $item->adderInfo->name,
                'تاریخ ثبت' => Jalalian::fromCarbon($item->created_at)->format("Y/m/d H:i:s"),
                'ویرایش کننده' => $item->editorInfo?->name,
                'تاریخ ویرایش' => Jalalian::fromCarbon($item->updated_at)->format("Y/m/d H:i:s"),
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
        }, $export_filename);
    }
}