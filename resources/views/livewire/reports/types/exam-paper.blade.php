<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title></title>
    <style>
        * {
            font-family: 'Vazir', sans-serif !important;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4;
            margin: 8mm 10mm;
        }

        body {
            margin: 0;
            padding: 0;
            font-size: 11px;
        }

        .exam-paper {
            max-width: 100%;
            padding: 0.3rem;
            margin: 0;
        }

        .in-his-name {
            text-align: center;
            font-size: 0.65rem;
            font-weight: 600;
            margin-bottom: 3px;
            color: #2c4c6c;
        }

        .question-row {
            display: grid;
            grid-template-columns: 60px 1fr 70px;
            padding: 5px 8px;
            border-bottom: 1px solid #e2e8f0;
            align-items: start;
            background: white;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .question-row:nth-child(even) {
            background: #fefcf7;
        }

        .q-num {
            font-weight: 600;
            color: #1f4e6e;
            font-size: 0.85rem;
        }

        .q-text {
            font-size: 0.8rem;
            line-height: 1.3;
            color: #1f2d3a;
            padding-right: 8px;
        }

        .q-marks {
            font-weight: 600;
            color: #2c6e2c;
            text-align: center;
            font-size: 0.8rem;
        }

        .marks-header {
            background: #d9e2e8;
            display: grid;
            grid-template-columns: 60px 1fr 70px;
            padding: 5px 8px;
            font-weight: bold;
            border-radius: 6px 6px 0 0;
            margin-top: 12px;
            font-size: 0.8rem;
        }

        .section-badge {
            background: #cbdbe6;
            padding: 4px 10px;
            font-weight: bold;
            font-size: 0.95rem;
            border-radius: 16px;
            display: inline-block;
            margin: 10px 0 4px 0;
        }

        .mcq-note {
            font-style: italic;
            background: #fef5e7;
            padding: 3px 10px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 10px;
            font-size: 0.75rem;
        }

        .footer-note {
            text-align: center;
            margin-top: 20px;
            padding-top: 12px;
            border-top: 2px solid #cfdde6;
            font-size: 0.85rem;
            font-weight: 500;
            color: #2c5a2e;
            font-style: italic;
        }

        @media (max-width: 750px) {
            .exam-paper {
                padding: 0.3rem;
            }

            .question-row {
                grid-template-columns: 45px 1fr 55px;
                gap: 4px;
                padding: 4px 6px;
            }

            .q-text {
                font-size: 0.75rem;
            }

            .marks-header {
                grid-template-columns: 45px 1fr 55px;
                font-size: 0.7rem;
            }
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            border-bottom: 1.5px solid #2c3e4e;
            padding-bottom: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .logo-placeholder {
            background: #f0f3f5;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            border: 1px dashed #aaa;
            font-size: 20px;
            color: #4a627a;
        }

        .logo-placeholder img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .school-info {
            text-align: center;
            flex: 1;
        }

        .school-name {
            font-size: 1.1rem;
            font-weight: bold;
            color: #1a3e50;
        }

        .header-details {
            display: flex;
            justify-content: space-between;
            background: #f9fafb;
            padding: 6px 12px;
            border-radius: 8px;
            margin: 8px 0 10px 0;
            flex-wrap: wrap;
            gap: 6px;
            border: 1px solid #dce4ec;
            font-size: 0.75rem;
        }

        hr.dashed {
            margin: 10px 0;
            border: 0;
            border-top: 1px dashed #b9cedf;
        }

        .passage-box {
            font-family: inherit;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            page-break-after: avoid;
            break-after: avoid;
            padding: 10px !important;
            margin: 6px 0 10px 0 !important;
            font-size: 0.8rem;
        }

        .multi-part-question .question-row:first-of-type {
            border-top: 1px solid #e2e8f0;
        }

        .multi-part-question .q-num {
            font-weight: 600;
            color: #2c6e2c;
        }

        .q-title {
            margin-bottom: 3px;
        }

        .q-options {
            margin-top: 3px;
            padding-right: 6px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px 20px;
        }

        .option {
            display: inline-flex;
            align-items: baseline;
            margin-bottom: 1px;
            font-size: 0.78rem;
        }

        .option.long-option {
            flex: 0 0 calc(50% - 10px);
            max-width: calc(50% - 10px);
        }

        .option.short-option {
            flex: 0 0 calc(25% - 15px);
            max-width: calc(25% - 15px);
        }

        .q-title img {
            page-break-inside: avoid;
            max-width: 100%;
            max-height: 80px;
            display: block;
            margin: 2px 0;
            height: auto;
        }

        .q-title p {
            margin: 0;
            padding: 0;
        }

        .q-title {
            line-height: 1.3;
        }

        .q-title br {
            line-height: 0;
        }

        .q-title>* {
            margin-top: 0;
            margin-bottom: 2px;
        }

        .exam-title {
            text-align: center;
            margin: 3px 0 0px 0;
            font-weight: bold;
            font-size: 1.1rem;
            color: #004070;
        }

        .student-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            margin-bottom: 3px;
        }

        @media (max-width: 600px) {
            .option.long-option,
            .option.short-option {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* فشرده‌سازی بیشتر برای صفحات */
        .compact {
            line-height: 1.2;
        }

        /* کاهش فاصله بین بخش‌ها */
        .section-spacer {
            margin-top: 6px;
        }
    </style>
</head>
<body>
<div class="exam-paper compact">
    <div class="in-his-name">
        <p>IN HIS NAME</p>
    </div>

    <div class="header-top">
        <div class="logo-left">
            <div class="logo-placeholder">
                <img src="{{ public_path('build/images/ministry_logo.png') }}" alt="logo-placeholder" />
            </div>
        </div>
        <div class="school-info">
            <div class="school-name">Monji International Schools</div>
            <div style="font-size: 0.75rem;">{{ $classroom_course->classroomInfo->academicYearInfo->schoolInfo->name }}</div>
        </div>
        <div class="logo-right">
            <div class="logo-placeholder">
                <img src="{{ public_path('build/images/logo.png') }}" alt="logo-placeholder" />
            </div>
        </div>
    </div>

    <div class="exam-title">
        2<sup>nd</sup> End-Term Examination 2025-2026
    </div>

    <div class="header-details">
        <div class="detail-item">Date: {{ \App\Service\ExamService::getExamDate($classroom_course->id,$term_value) }}</div>
        <div class="detail-item">Subject: {{ $classroom_course->courseInfo->name }}</div>
        <div class="detail-item">Time: {{ \App\Service\ExamService::getExamDuration($classroom_course->id,$term_value) }} min</div>
        <div class="detail-item">Marks: _____/60</div>
        <div class="detail-item">{{ $classroom_course->classroomInfo->gradeInfo->name }}</div>
    </div>

    <div class="student-info">
        <span>Checked by: ____________________</span>
        <span>Student's Name: _________________________________</span>
    </div>

    <div style="margin-top: 10px;">
        @foreach($questions as $question)
            @php
                $letters = ['A', 'B', 'C', 'D'];
                $index = 0;
            @endphp
            @switch($question['question_type'])
                @case('multiple_choice')
                    <div class="question-row">
                        <div class="q-num">{{ $loop->iteration }}.</div>

                        <div class="q-text">
                            <div class="q-title">
                                {!! strip_tags($question['title'], '<sup><sub><img>') !!}
                            </div>

                            <div class="q-options">
                                @foreach($question['options'] as $option)
                                    @php
                                        $cleanOption = strip_tags($option);
                                        $optionLength = mb_strlen($cleanOption);
                                        $isLong = $optionLength > 20;
                                    @endphp
                                    <div class="option {{ $isLong ? 'long-option' : 'short-option' }}">
                                        {{ $letters[$index++] }}) {!! strip_tags($option, '<sup><sub><img>') !!}
                                    </div>
                                @endforeach
                            </div>
                        </div>

{{--                        <div class="q-marks">[{{ $question['marks'] ?? 1 }}]</div>--}}
                    </div>
                    @break

                @case('multipart_question')
                    <div class="multi-part-question">
                        <div class="passage-box" style="background: #f9f7f0; padding: 10px; border-radius: 10px; border-left: 4px solid #2c6e2c; margin: 4px 0 8px 0; font-size: 0.78rem;">
                            {!! $question['title'] !!}
                        </div>

                        @foreach($question['sub_questions'] as $sub_question)
                            @php
                                $letters = ['A', 'B', 'C', 'D'];
                                $index = 0;
                            @endphp
                            <div class="question-row">
                                <div class="q-num">{{ $loop->iteration }}.</div>
                                <div class="q-text">
                                    <div class="q-title">
                                        {!! strip_tags($sub_question['question'], '<sup><sub><img>') !!}
                                    </div>

                                    <div class="q-options">
                                        @foreach($sub_question['options'] as $option)
                                            @php
                                                $cleanOption = strip_tags($option);
                                                $optionLength = mb_strlen($cleanOption);
                                                $isLong = $optionLength > 20;
                                            @endphp
                                            <div class="option {{ $isLong ? 'long-option' : 'short-option' }}">
                                                {{ $letters[$index++] }}) {!! strip_tags($option, '<sup><sub><img>') !!}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
{{--                                <div class="q-marks">[{{ $sub_question['marks'] ?? 1 }}]</div>--}}
                            </div>
                        @endforeach
                    </div>
                    @break
            @endswitch
        @endforeach
    </div>

    <div class="footer-note">
        I'm proud of you no matter what, my love!
    </div>
    <hr class="dashed">
    <div style="font-size: 0.6rem; text-align: center; color: #6f8faa;">"Best of Akhlaq leads to highest honor" - Imam Ali (AS)</div>
</div>
</body>
</html>