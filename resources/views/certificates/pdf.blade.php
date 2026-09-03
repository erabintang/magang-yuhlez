<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; margin: 0; padding: 0; }
        .certificate {
            width: 297mm;
            height: 210mm;
            padding: 20mm;
            box-sizing: border-box;
            position: relative;
        }
        .border {
            position: absolute;
            top: 10mm;
            left: 10mm;
            right: 10mm;
            bottom: 10mm;
            border: 3px solid #1e293b;
        }
        .border-inner {
            position: absolute;
            top: 14mm;
            left: 14mm;
            right: 14mm;
            bottom: 14mm;
            border: 1px solid #94a3b8;
        }
        .header { text-align: center; margin-top: 30mm; }
        .header h1 {
            font-size: 28pt;
            color: #1e293b;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        .header h2 {
            font-size: 14pt;
            color: #f59e0b;
            margin: 5px 0 0 0;
            font-weight: normal;
        }
        .title {
            text-align: center;
            font-size: 18pt;
            color: #1e293b;
            margin-top: 15mm;
            text-transform: uppercase;
            font-weight: bold;
        }
        .body {
            text-align: center;
            font-size: 12pt;
            color: #334155;
            margin-top: 8mm;
            line-height: 1.8;
        }
        .body .name {
            font-size: 18pt;
            font-weight: bold;
            color: #1e293b;
            text-decoration: underline;
        }
        .details {
            margin-top: 10mm;
            font-size: 10pt;
            color: #64748b;
            text-align: center;
            line-height: 1.6;
        }
        .footer {
            position: absolute;
            bottom: 25mm;
            left: 20mm;
            right: 20mm;
            display: flex;
            justify-content: space-between;
        }
        .signature {
            text-align: center;
            width: 30%;
        }
        .signature .line {
            border-bottom: 1px solid #1e293b;
            margin-top: 15mm;
            margin-bottom: 3mm;
        }
        .signature .label {
            font-size: 9pt;
            color: #64748b;
        }
        .cert-number {
            position: absolute;
            top: 20mm;
            right: 25mm;
            font-size: 9pt;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border"></div>
        <div class="border-inner"></div>

        <div class="cert-number">No. {{ $certificate->certificate_number ?? '-' }}</div>

        <div class="header">
            <h1>Sertifikat</h1>
            <h2>Program Magang</h2>
        </div>

        <div class="title">Penghargaan Sertifikat Magang</div>

        <div class="body">
            Diberikan kepada<br>
            <span class="name">{{ $certificate->intern->name ?? '-' }}</span>
            <br><br>
            Telah berhasil menyelesaikan program magang
            <br>
            <strong>{{ $certificate->program->title ?? '-' }}</strong>
            <br>
            di <strong>{{ $certificate->program->company->name ?? '-' }}</strong>
        </div>

        <div class="details">
            Periode: {{ $certificate->program->program_start?->format('d M Y') ?? '-' }} - {{ $certificate->program->program_end?->format('d M Y') ?? '-' }}
            <br>
            Tanggal Terbit: {{ $certificate->issued_at?->format('d M Y') ?? '-' }}
        </div>

        <div class="footer">
            <div class="signature">
                <div class="line"></div>
                <div class="label">{{ $certificate->program->company->name ?? 'Perusahaan' }}</div>
            </div>
            <div class="signature">
                <div class="line"></div>
                <div class="label">YUHLEZ Software House</div>
            </div>
        </div>
    </div>
</body>
</html>
