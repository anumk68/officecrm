<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Salary Slip - {{ $slip->month }}</title>

    <style>
    @page {
            size: A4;
            margin: 12mm 12mm 28mm 12mm;
        }
        /* FULL WHITE BACKGROUND */
        body {
            margin: 0;
            padding: 0;
            background: #ffffff !important;
            font-family: Arial, sans-serif;
            font-size: 13px;
        }

        h2 {
            color: #2a4dbe
        }

        /* A4 CONTENT WRAPPER */
        .container {
            width: 100%;
            background: #ffffff;
            position: relative;
            padding: 10px 15px 80px;
            /* bottom padding for footer space */
            box-sizing: border-box;
        }

        /* HEADER */
        .company-header {
            text-align: center;
            margin-bottom: 10px;
        }

        .title-box {
            text-align: center;
            font-weight: bold;
            border: 1px solid #000;
            padding: 8px;
            margin: 14px 0;
            font-size: 15px;
        }

        /* ALL TABLES */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table th,
        table td {
            border: 1px solid #888;
            padding: 6px;
        }

        .right {
            text-align: right;
        }

        /* FIXED PDF FOOTER */
        .footer {
            position: fixed;
            bottom: 0px;
            left: 0;
            right: 0;
            width: 100%;
            padding: 8px 10px;
            border-top: 1px solid #ccc;
            font-size: 11px;
        }

        .footer-left {
            float: left;
            width: 50%;
            text-align: left;
            line-height: 16px;
        }

        .footer-right {
            float: right;
            width: 50%;
            text-align: right;
            line-height: 16px;
        }

        .clear {
            clear: both;
        }
    </style>

</head>

<body>

    @php
        // ----------- AUTO NET PAY CALCULATION --------------
        $earnings = $slip->basic + $slip->incentives + $slip->overtime;
        $deductions = $slip->unpaid_leave_amount + $slip->late_coming;
        $netPay = $earnings - $deductions;

        // ----------- NUMBER TO WORDS (WITHOUT NumberFormatter) --------------
        function convertNumberToWords($num)
        {
            $ones = [
                0 => 'zero',
                1 => 'one',
                2 => 'two',
                3 => 'three',
                4 => 'four',
                5 => 'five',
                6 => 'six',
                7 => 'seven',
                8 => 'eight',
                9 => 'nine',
                10 => 'ten',
                11 => 'eleven',
                12 => 'twelve',
                13 => 'thirteen',
                14 => 'fourteen',
                15 => 'fifteen',
                16 => 'sixteen',
                17 => 'seventeen',
                18 => 'eighteen',
                19 => 'nineteen',
                20 => 'twenty',
                30 => 'thirty',
                40 => 'forty',
                50 => 'fifty',
                60 => 'sixty',
                70 => 'seventy',
                80 => 'eighty',
                90 => 'ninety',
            ];

            $levels = [
                10000000 => 'crore',
                100000 => 'lakh',
                1000 => 'thousand',
                100 => 'hundred',
            ];

            if ($num < 21) {
                return ucfirst($ones[$num]);
            }
            if ($num < 100) {
                return ucfirst($ones[10 * floor($num / 10)]) . ($num % 10 ? ' ' . $ones[$num % 10] : '');
            }

            foreach ($levels as $value => $word) {
                if ($num >= $value) {
                    return ucfirst(convertNumberToWords(floor($num / $value))) .
                        " $word " .
                        convertNumberToWords($num % $value);
                }
            }
        }

        $netPayWords = convertNumberToWords($netPay);
    @endphp

    <div class="container">

        <!-- HEADER -->
        <div class="company-header">
            @if (!empty($slip->company_logo))
                <img src="{{ storage_path('app/public/' . $slip->company_logo) }}"
                    style="max-height:80px; margin-bottom:10px;">
            @endif

            <h2>{{ $slip->company_name }}</h2>
            <p>{{ $slip->company_tagline }}</p>
        </div>

        <!-- TITLE -->
        <div class="title-box">
            Pay Slip for the Month of {{ \Carbon\Carbon::parse($slip->month)->format('F - Y') }}
        </div>

        <!-- Employee Info -->
        <table>
            <tr>
                <th>Employee Name</th>
                <td>{{ $slip->employee_name }}</td>

                <th>Employee ID</th>
                <td>{{ $slip->emp_code }}</td>
            </tr>

            <tr>
                <th>Designation</th>
                <td>{{ $slip->designation }}</td>

                <th>Joining Date</th>
                <td>{{ $slip->joining_date?->format('d M Y') }}</td>
            </tr>

            <tr>
                <th>Bank Account Number</th>
                <td>{{ $slip->bank_account }}</td>

                <th>Generated On</th>
                <td>{{ now()->format('d M Y') }}</td>
            </tr>
        </table>

        <!-- EARNING + DEDUCTION + ATTENDANCE -->
        <table>
            <tr class="center">
                <th colspan="2">Earning Info</th>
                <th colspan="2">Deduction Info</th>
                <th colspan="2">Attendance Info</th>
            </tr>

            <tr class="center">
                <th>Earning Head</th>
                <th>Paid</th>
                <th>Deduction Head</th>
                <th>Deducted</th>
                <th>Days Status</th>
                <th>Days</th>
            </tr>

            <tr>
                <td>Basic</td>
                <td class="right">{{ number_format($slip->basic, 2) }}</td>

                <td>Unpaid Leaves</td>
                <td class="right">{{ number_format($slip->unpaid_leave_amount, 2) }}</td>

                <td>Working</td>
                <td>{{ $slip->working_days }}</td>
            </tr>

            <tr>
                <td>Incentives</td>
                <td class="right">{{ number_format($slip->incentives, 2) }}</td>

                <td>Late Coming</td>
                <td class="right">{{ number_format($slip->late_coming, 2) }}</td>

                <td>On Duty</td>
                <td>{{ $slip->on_duty }}</td>
            </tr>

            <tr>
                <td>Overtime</td>
                <td class="right">{{ number_format($slip->overtime, 2) }}</td>

                <td>Unpaid Leave Days</td>
                <td class="right">{{ $slip->unpaid_leave_days }}</td>

                <td>Leave</td>
                <td>{{ $slip->unpaid_leave_days }}</td>
            </tr>

            <tr class="center">
                <th>Total</th>
                <th class="right">{{ number_format($earnings, 2) }}</th>

                <th>Total</th>
                <th class="right">{{ number_format($deductions, 2) }}</th>

                <th colspan="2"></th>
            </tr>
        </table>

        <!-- NET PAY -->
        <div class="net-box">
            Net Payable:
            {{ number_format($netPay, 2) }}
        </div>

        <p><strong>In words:</strong> {{ $netPayWords }} Only.</p>

        <!-- Additional Employee Details -->
        <table>
            <tr>
                <th>Father Name</th>
                <td>{{ $slip->father_name }}</td>

                <th>Address</th>
                <td>{{ $slip->address }}</td>
            </tr>

            <tr>
                <th>DOB</th>
                <td>{{ $slip->dob?->format('d M Y') }}</td>

                <th>Mobile Number</th>
                <td>{{ $slip->mobile }}</td>
            </tr>

            <tr>
                <th>Email ID</th>
                <td>{{ $slip->email }}</td>

                <th> </th>
                <td>
                    @if (!empty($slip->company_stamp))
                        <img src="{{ storage_path('app/public/' . $slip->company_stamp) }}"
                            style="height:80px; opacity:0.8;">
                    @endif
                </td>
            </tr>
        </table>

        <div class="footer">
            <div class="footer-left">
                {!! nl2br($slip->company_address) !!}
            </div>

            <div class="footer-right">
                <a href="tel:+919915954999">+91 99159 54999</a><br>
                <a href="mailto:hr@digirushsolutions.com">hr@digirushsolutions.com</a><br>
                <a href="https://www.digirushsolutions.com" target="_blank">www.digirushsolutions.com</a>
            </div>
        </div>



    </div>

</body>

</html>
