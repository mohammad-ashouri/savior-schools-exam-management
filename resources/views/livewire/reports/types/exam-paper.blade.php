<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* تنظیمات مخصوص PDF / چاپ */
        @page {
            size: A4;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .exam-paper {
            max-width: 100%;
            padding: 1rem; /* کاهش padding داخلی */
            margin: 0;
        }

        .in-his-name {
            text-align: center; /* قبلاً right بود */
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: #2c4c6c;
        }

        /* --- کلید اصلی صفحه‌بندی خودکار --- */
        .question-row {
            display: grid;
            grid-template-columns: 80px 1fr 100px;
            padding: 12px 12px;
            border-bottom: 1px solid #e2e8f0;
            align-items: start;
            background: white;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .section-header, .section-badge, .marks-header {
            page-break-after: avoid;
            break-after: avoid;
        }

        .question-row:nth-child(even) {
            background: #fefcf7;
        }

        .q-num {
            font-weight: 600;
            color: #1f4e6e;
        }

        .q-text {
            font-size: 0.92rem;
            line-height: 1.4;
            color: #1f2d3a;
            padding-right: 12px;
        }

        .q-marks {
            font-weight: 600;
            color: #2c6e2c;
            text-align: center;
        }

        .marks-header {
            background: #d9e2e8;
            display: grid;
            grid-template-columns: 80px 1fr 100px;
            padding: 8px 12px;
            font-weight: bold;
            border-radius: 8px 8px 0 0;
            margin-top: 20px;
        }

        .section-badge {
            background: #cbdbe6;
            padding: 6px 12px;
            font-weight: bold;
            font-size: 1.1rem;
            border-radius: 20px;
            display: inline-block;
            margin: 16px 0 8px 0;
        }

        .mcq-note {
            font-style: italic;
            background: #fef5e7;
            padding: 4px 12px;
            border-radius: 24px;
            display: inline-block;
            margin-bottom: 18px;
            font-size: 0.85rem;
        }

        .footer-note {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #cfdde6;
            font-size: 1rem;
            font-weight: 500;
            color: #2c5a2e;
            font-style: italic;
        }

        @media (max-width: 750px) {
            .exam-paper {
                padding: 1rem;
            }

            .question-row {
                grid-template-columns: 60px 1fr 70px;
                gap: 6px;
            }

            .q-text {
                font-size: 0.85rem;
            }

            .marks-header {
                grid-template-columns: 60px 1fr 70px;
                font-size: 0.8rem;
            }
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            border-bottom: 2px solid #2c3e4e;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }

        .logo-placeholder {
            background: #f0f3f5;
            width: 90px;
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            border: 1px dashed #aaa;
            font-size: 28px;
            color: #4a627a;
        }

        .school-info {
            text-align: center;
            flex: 1;
        }

        .school-name {
            font-size: 1.3rem;
            font-weight: bold;
            color: #1a3e50;
        }

        .header-details {
            display: flex;
            justify-content: space-between;
            background: #f9fafb;
            padding: 12px 16px;
            border-radius: 12px;
            margin: 18px 0 20px 0;
            flex-wrap: wrap;
            gap: 12px;
            border: 1px solid #dce4ec;
        }

        hr.dashed {
            margin: 16px 0;
            border: 0;
            border-top: 1px dashed #b9cedf;
        }

        .passage-box {
            font-family: inherit;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .multi-part-question .question-row:first-of-type {
            border-top: 1px solid #e2e8f0;
        }

        /* Optional: slightly indent sub-questions */
        .multi-part-question .q-num {
            font-weight: 600;
            color: #2c6e2c;
        }

        .q-title {
            margin-bottom: 6px;
        }

        .q-options {
            margin-top: 6px;
            padding-right: 10px;
        }

        .option {
            display: block;
            margin-bottom: 4px;
        }

        .q-title img {
            page-break-inside: avoid;
            max-width: 100%;
        }

        .q-title img {
            display: block;
            margin: 4px 0;
            max-width: 100%;
            height: auto;
        }

        .q-title p {
            margin: 0;
            padding: 0;
        }

        .q-title {
            line-height: 1.4;
        }

        .q-title br {
            line-height: 0;
        }

        .q-title > * {
            margin-top: 0;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
<div class="exam-paper">
    <div class="in-his-name">
        <p>IN HIS NAME</p>
    </div>

    <div class="header-top">
        <div class="logo-left">
            <div class="logo-placeholder">📖</div>
        </div>
        <div class="school-info">
            <div class="school-name">Monji International Schools</div>
            <div class="kawthar">{{ $classroom_course->classroomInfo->academicYearInfo->schoolInfo->name }}</div>
        </div>
        <div class="logo-right">
            <div class="logo-placeholder">⭐</div>
        </div>
    </div>

    <div style="text-align: center; margin: 5px 0 0px 0; font-weight: bold; font-size: 1.35rem; color: #004070;">
        2<sup>nd</sup> End-Term Examination 2025-2026
    </div>

    <div class="header-details">
        <div class="detail-item"> Date: {{ now()->format('Y-m-d') }}</div>
        <div class="detail-item"> Subject: {{ $classroom_course->courseInfo->name }}</div>
        <div class="detail-item"> Time: ______90______ Minutes</div>
        <div class="detail-item"> Marks: _______________ out of <strong>60</strong></div>
        <div class="detail-item"> {{ $classroom_course->classroomInfo->gradeInfo->name }}</div>
        <div class="detail-item"> No. of Pages: @totalPages</div>
    </div>

    <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 5px;">
        <span>Checked by: ____________________</span>
        <span>Student's Name: _________________________________</span>
    </div>

    <div style="margin-top: 24px;">


        @foreach($questions as $question)
            @switch($question['question_type'])
                @case('multiple_choice')
                    <div class="marks-header">
                        <div>Sec/Q#</div>
                        <div>Questions</div>
                    </div>
                    <div class="question-row">
                        <div class="q-num">{{ $loop->iteration }}</div>

                        <div class="q-text">
                            <div class="q-title">
                                @if(str_contains($question['title'], '<img'))
                                    {!! $question['title'] !!}
                                @else
                                    {!! strip_tags($question['title']) !!}
                                @endif
                            </div>

                            <div class="q-options">
                                @foreach($question['options'] as $option)
                                    <div class="option">
                                        {{ $loop->iteration }}) {!! strip_tags($option) !!}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @break
                @case('multipart_question')
                    <div class="multi-part-question" style="page-break-inside: avoid;">
                        <!-- Main text / passage -->
                        <div class="passage-box"
                             style="background: #f9f7f0; padding: 16px; border-radius: 12px; border-left: 5px solid #2c6e2c; margin-bottom: 16px;">
                            {!! $question['title'] !!}
                        </div>

                        <!-- Sub-questions (each behaves like a normal question-row) -->
                        @foreach($question['sub_questions'] as $sub_question)
                            <div class="question-row">
                                <div class="q-num">{{ $loop->iteration }}</div>
                                <div class="q-text">
                                    <div class="q-title">
                                        @if(str_contains($sub_question['question'], '<img'))
                                            {!! $sub_question['question'] !!}
                                        @else
                                            {!! strip_tags($sub_question['question']) !!}
                                        @endif
                                    </div>

                                    <div class="q-options">
                                        @foreach($sub_question['options'] as $option)
                                            <div class="option">
                                                {{ $loop->iteration }}) {!! strip_tags($option) !!}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @break
            @endswitch
        @endforeach
        <!-- Multi-part question: passage with sub-questions -->
        <div style="text-align: right; font-weight: bold; margin-top: 16px; padding: 8px 12px; background: #f1f5f9; border-radius: 12px;">
            Total Marks: 60 (All questions carry 1 mark each)
        </div>
    </div>

    <div class="footer-note">
        I'm proud of you no matter what, my love!
    </div>
    <hr class="dashed">
    <div style="font-size: 0.7rem; text-align: center; color: #6f8faa;">"Best of Akhlaq leads to highest honor" - Imam
        Ali (AS)
    </div>

</div>
</body>
</html>