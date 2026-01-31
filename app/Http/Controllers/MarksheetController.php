<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Illuminate\Support\Facades\Auth;
class MarksheetController extends Controller
{
    
    private const CW_MAX = 40;
    private const MID_MAX = 20;
    private const FINAL_MAX = 40;

    public function index()
    {
        return view('dashboard.dean');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // Load spreadsheet
        try {
            $spreadsheet = IOFactory::load($path);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error loading spreadsheet: ' . $e->getMessage()]);
        }

        $sheet = $spreadsheet->getActiveSheet();

        // Read all rows with formulas evaluated
        $rows = [];
        foreach ($sheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                // Read the calculated value (important for component scores if they contain formulas, or for finding the 'Total' header)
                $rowData[] = $cell->getCalculatedValue();
            }
            $rows[] = $rowData;
        }

        // Find header row (the row containing 'Total')
        $headerRowIndex = null;
        foreach ($rows as $index => $row) {
            foreach ($row as $cell) {
                // Look for 'Total' (case-insensitive, trimmed)
                if (strtolower(trim((string)$cell)) === 'total') {
                    $headerRowIndex = $index;
                    break 2; // Found header, exit loops
                }
            }
        }

        if ($headerRowIndex === null) {
            return back()->withErrors(['error' => 'Could not find a "Total" column (the student data header row) in the Excel file.']);
        }
        
        //Capture all administrative rows above the header ---
        // $rows uses 0-based indexing. $headerRowIndex is the index of the header row.

$adminRows = array_slice($rows, 0, $headerRowIndex);

$adminRows = array_filter($adminRows, function($row) {
return array_filter($row); 
});

// Now, $adminRows only contains rows with at least one piece of data.
        // -----------------------------------------------------------------

        // Identify necessary header columns
        $headers = array_map('trim', $rows[$headerRowIndex]);

        // Find column indices
        $totalIndex = array_search('Total', $headers); // Still finding this index for completeness, but its value will be ignored.
        
        $nameIndex = false;
        $idxIndex = false; 
        $cwIndex = false;
        $midIndex = false;
        $finalIndex = false;

        // --- FIXED LOGIC FOR INDEX AND NAME IDENTIFICATION ---
        $tempNameIndex = false;
        $tempIdxIndex = false;

        foreach ($headers as $i => $header) {
            $normalizedHeader = strtolower(trim($header));

            if (stripos($normalizedHeader, 'name') !== false) {
                if ($tempNameIndex === false) {
                    $tempNameIndex = $i;
                }
            } elseif (stripos($normalizedHeader, 'index') !== false || stripos($normalizedHeader, 'id') !== false) {
                if ($tempIdxIndex === false) {
                    $tempIdxIndex = $i;
                }
            }

            // More robust checking for components, ensuring they are not "Total"
            if (stripos($normalizedHeader, 'cw') !== false && $normalizedHeader !== 'cw total') {
                $cwIndex = $i;
            }
            if (stripos($normalizedHeader, 'mt') !== false) {
                $midIndex = $i;
            }
            if (stripos($normalizedHeader, 'final') !== false) {
                $finalIndex = $i;
            }
        }

        // Final assignment logic: 
        if ($tempIdxIndex !== false) {
            $idxIndex = $tempIdxIndex;
        }
        
        // Only assign name if it's found AND it's not the same column as index
        if ($tempNameIndex !== false && $tempNameIndex !== $idxIndex) {
            $nameIndex = $tempNameIndex;
        }

        // Fallback for index (ID is mandatory for student identification)
        if ($idxIndex === false) {
           $idxIndex = 0; 
        }
   
        if ($cwIndex === false || $midIndex === false || $finalIndex === false) {
            return back()->withErrors(['error' => 'Could not find required component columns (CW, MT, FINAL) to perform adjustment logic.']);
        }


    $students = [];
    $totals = [];
    $emptyOrZeroStudents = [];

    for ($i = $headerRowIndex + 1; $i < count($rows); $i++) {
        $row = $rows[$i];

        $cw = isset($row[$cwIndex]) && is_numeric($row[$cwIndex]) ? (float)$row[$cwIndex] : 0;
        $mid = isset($row[$midIndex]) && is_numeric($row[$midIndex]) ? (float)$row[$midIndex] : 0;
        $final = isset($row[$finalIndex]) && is_numeric($row[$finalIndex]) ? (float)$row[$finalIndex] : 0;

        $calculatedTotal = $cw + $mid + $final;

        $studentData = [
            'name' => ($nameIndex !== false && isset($row[$nameIndex]) && $nameIndex !== $idxIndex)
                        ? trim((string)$row[$nameIndex])
                        : '',
            'index' => (isset($row[$idxIndex]) && !empty(trim((string)$row[$idxIndex])))
                        ? trim((string)$row[$idxIndex])
                        : '',
            'cw' => $cw,
            'mid' => $mid,
            'final' => $final,
            'total' => $calculatedTotal,
        ];

     if ($calculatedTotal <= 0) {
    // Only students with both valid index and name get added here
    if (!empty($studentData['index']) && !empty($studentData['name'])) {
        
        // Exclude obvious non-student rows by filtering out common unwanted keywords (case-insensitive)
        $excludedKeywords = ['submitted', 'student', 'index', 'name', 'total', 'row', 'score'];

        $nameLower = strtolower($studentData['name']);
        $indexLower = strtolower($studentData['index']);
        
        $hasExcludedKeyword = false;
        foreach ($excludedKeywords as $keyword) {
            if (str_contains($nameLower, $keyword) || str_contains($indexLower, $keyword)) {
                $hasExcludedKeyword = true;
                break;
            }
        }

        if (!$hasExcludedKeyword) {
            $emptyOrZeroStudents[] = $studentData;
        }
    }
}
 elseif ($calculatedTotal <= 100) {
            $students[] = $studentData;
            $totals[] = $calculatedTotal;
        }
    }


        // Sort students by index number (ascending)
usort($students, function ($a, $b) {
    // Extract numeric part if index contains text (like "ST123")
    $aIndex = preg_replace('/[^0-9]/', '', $a['index']);
    $bIndex = preg_replace('/[^0-9]/', '', $b['index']);

    // If numeric parts exist, compare numerically
    if (is_numeric($aIndex) && is_numeric($bIndex)) {
        return (int)$aIndex <=> (int)$bIndex;
    }

    // Otherwise compare as strings
    return strcmp($a['index'], $b['index']);
});


        if (empty($students)) {
            return back()->withErrors(['error' => 'No valid student data rows found after the header.']);
        }

        // Group into grade ranges (for histogram view)
        $ranges = [];
        for ($i = 0; $i <= 90; $i += 10) {
            $min = $i;
            $max = $i + 9;
            $label = ($i == 90) ? "90-100" : "$min-$max";

            $count = collect($totals)->filter(function ($score) use ($min, $max) {
                return $score >= $min && $score <= (($max == 99) ? 100 : $max);
            })->count();

            $ranges[$label] = $count;
        }

        // Store students, admin rows data, and filename in session for download
 session([
    'students' => $students,
    'empty_or_zero_students' => $emptyOrZeroStudents, // <---- NEW
    'original_filename' => $originalFileName,
    'admin_rows' => $adminRows
]);
        return view('dashboard.dean', [
            'labels' => array_keys($ranges),
            'counts' => array_values($ranges),
            'totalStudents' => count($totals),
            'totals' => $totals ,
            'filename'=>$originalFileName
        ]);
        
    }
/**
* Clears the session data related to the marksheet results 
* and redirects to the clean upload page.
*/
public function resetUpload(){
    // 1. Clear any session data related to the previous results
    session()->forget(['students', 'original_filename', 'admin_rows']); 
    
    // 2. Redirect back to the main upload form.
    return redirect()->route('marksheet.index'); // <-- Using the necessary route name
}



public function updateTargetValues(Request $request)
{
    session([
        'targetMean' => $request->input('targetMean'),
        'targetStdDev' => $request->input('targetStdDev')
    ]);
    return response()->json(['success' => true]);
}

    public function download(Request $request){
        $students = session('students', []);
        $originalFileName = session('original_filename', 'marksheet');
        $adminRows = session('admin_rows', []); // Retrieve the administrative rows

        if (empty($students)) {
            return back()->withErrors(['error' => 'No student data found in session. Please upload again.']);
        }

        // Extract all totals
        $totals = array_column($students, 'total');
        $count = count($totals);

        if ($count === 0) {
            return back()->withErrors(['error' => 'No students found for calculation.']);
        }
        
        // --- 1. Calculate Original Mean (μ) and Standard Deviation (σ) ---
        $mean = array_sum($totals) / $count;
        // Use $count for population standard deviation, or $count - 1 for sample (using sample here)
        $variance = array_sum(array_map(fn($x) => pow($x - $mean, 2), $totals)) / (($count > 1) ? $count - 1 : 1);
        $stdDev = sqrt($variance);

        // --- 2. Calculate Highest (H) and Lowest (L) Scores ---
        $highest = max($totals);
        $lowest = min($totals);

        // --- 3. Calculate Target Mean (μ') and Target Standard Deviation (σ') ---
       
 // --- 3. Calculate Target Mean (μ') and Target Standard Deviation (σ') ---

// 🎯 PRIORITY 1: Get values from the request (submitted hidden fields)
$requestTargetMean = $request->input('targetMean');
$requestTargetStdDev = $request->input('targetStdDev');

if (is_numeric($requestTargetMean) && is_numeric($requestTargetStdDev)) {
    // Use the values submitted from the form (the ones the user last set on the UI)
    $targetMean = floatval($requestTargetMean);
    $targetStdDev = floatval($requestTargetStdDev);
} else {
    // PRIORITY 2: Fallback to the session value (set via AJAX)
    $targetMean = session('targetMean');
    $targetStdDev = session('targetStdDev');

    // PRIORITY 3: Fallback to calculated defaults if session is also empty
    if (!is_numeric($targetMean) || !is_numeric($targetStdDev)) {
        $targetMean = ($highest + $lowest) / 2;
        $targetStdDev = ($highest - $lowest > 0) ? ($highest - $lowest) / 4 : 0;
    }
}

        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Calculate row offsets based on the number of administrative rows
        $adminRowCount = count($adminRows);
        
        // New headers start immediately after the admin rows
        $headerRow = $adminRowCount + 1; 
        // Student data starts immediately after the new headers
        $startRow = $headerRow + 1; 
        // Find the last row used for student data
        $lastRow = count($students) + $startRow - 1; 

        // -------------------------------------------------------------
        // NEW STEP: WRITE ADMINISTRATIVE ROWS
        // -------------------------------------------------------------
        if (!empty($adminRows)) {
            // Write the array of rows starting at A1
            $sheet->fromArray($adminRows, NULL, 'A1');
            
            // Apply basic styling to the admin rows (A1 to max column, last admin row)
            $maxColIndex = max(array_map('count', $adminRows));
            $maxCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($maxColIndex);
            
            // Check if $maxCol is a valid column string before applying style
            if ($maxColIndex > 0) {
                $sheet->getStyle("A1:{$maxCol}{$adminRowCount}")->getFont()->setBold(true);
            }
        }
        // -------------------------------------------------------------


        // -------------------------------------------------------------
        // SET HEADERS (POSITION IS NOW DYNAMIC: $headerRow)
        // -------------------------------------------------------------
        $sheet->setCellValue('A' . $headerRow, 'Student Index');
        $sheet->setCellValue('B' . $headerRow, 'Student Name');
        $sheet->setCellValue('C' . $headerRow, 'CW (Adjusted)');
        $sheet->setCellValue('D' . $headerRow, 'Midterm (Adjusted)');
        $sheet->setCellValue('E' . $headerRow, 'Final (Adjusted)');
        $sheet->setCellValue('F' . $headerRow, 'New Calculated Total');
        $sheet->setCellValue('G' . $headerRow, 'Original Total');
        $sheet->setCellValue('H' . $headerRow, 'Z-Score');
        // Apply bold font to headers
        $sheet->getStyle("A{$headerRow}:H{$headerRow}")->getFont()->setBold(true);


        // Global Statistics (Headers remain K/L, start row is now 1)
        $statsStartRow = 1; 
        $statsEndRow = $statsStartRow + 5; 

        $sheet->setCellValue('K' . $statsStartRow++, 'Original Mean');
        $sheet->setCellValue('K' . $statsStartRow++, 'Original Std Dev');
        $sheet->setCellValue('K' . $statsStartRow++, 'Highest grade');
        $sheet->setCellValue('K' . $statsStartRow++, 'Lowest grade');
        $sheet->setCellValue('K' . $statsStartRow++, 'Target Mean');
        $sheet->setCellValue('K' . $statsStartRow++, 'Target Std Dev');
        
        $statsStartRow = 1; // Reset for values
        $sheet->setCellValue('L' . $statsStartRow++, round($mean, 2));
        $sheet->setCellValue('L' . $statsStartRow++, round($stdDev, 2));
        $sheet->setCellValue('L' . $statsStartRow++, round($highest, 2));
        $sheet->setCellValue('L' . $statsStartRow++, round($lowest, 2));
        $sheet->setCellValue('L' . $statsStartRow++, round($targetMean, 2));
        $sheet->setCellValue('L' . $statsStartRow++, round($targetStdDev, 2));


        // -------------------------------------------------------------
        // APPLY COLUMN STYLES (Headers, Data, and Borders)
        // -------------------------------------------------------------
        // Adjust data table range: A{headerRow} to H{lastRow}
        $dataTableRange = "A{$headerRow}:H{$lastRow}";

        // 1. Apply Borders to the main student data table
        $styleArrayDataTable = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $sheet->getStyle($dataTableRange)->applyFromArray($styleArrayDataTable);


        // 2. Apply Borders to the global statistics table (K1 to L6)
        $statsTableRange = "K1:L6";
        $styleArrayStatsTable = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM, 
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $sheet->getStyle($statsTableRange)->applyFromArray($styleArrayStatsTable);


        // Style for New Calculated Total (Column F) - Green
        $sheet->getStyle("F{$headerRow}:F{$lastRow}")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFD9EAD3'); // Light Green

        // Style for Old Total (Column G) - Red
        $sheet->getStyle("G{$headerRow}:G{$lastRow}")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFF4CCCC'); // Light Red


        // -------------------------------------------------------------
        // POPULATE STUDENT DATA (Starts at $startRow)
        // -------------------------------------------------------------
      $i = $startRow;
foreach ($students as $student) {
    $oldTotal = $student['total'];
   
 if ($oldTotal < 40) {
        // Keep original grades as is
        $newCW = $student['cw'];
        $newMid = $student['mid'];
        $newFinal = $student['final'];
        $newCalculatedTotal = $newCW + $newMid + $newFinal;
        $z = 0; 
      
    } else {
    // --- 4. Compute Z-score ---
    $z = ($stdDev > 0) ? ($oldTotal - $mean) / $stdDev : 0;

    // --- 5. Apply the new curve formula (This is the *target* total) ---
    $targetNewTotal = $targetMean + ($targetStdDev * $z);
    $targetNewTotal = min(100, max(0, $targetNewTotal)); // Clamp to 0–100

    // --- 6. Calculate Difference and Apply Distribution ---
    $difference = $targetNewTotal - $oldTotal;
    
    // Initialize new scores with current scores
    $newCW = $student['cw'];
    $newMid = $student['mid'];
    $newFinal = $student['final'];
    $remainingDifference = $difference; // This is the total amount to adjust the components by

    if ($remainingDifference >= 0) {
        // POSITIVE ADJUSTMENT: Apply to CW, then Mid, then Final, up to maxes

        // 1. Adjust CW (Max 40)
        $maxIncreaseCW = self::CW_MAX - $newCW;
        $increaseCW = min($remainingDifference, $maxIncreaseCW);
        $newCW += $increaseCW;
        $remainingDifference -= $increaseCW;

        // 2. Adjust Midterm (Max 20)
        if ($remainingDifference > 0) {
            $maxIncreaseMid = self::MID_MAX - $newMid;
            $increaseMid = min($remainingDifference, $maxIncreaseMid);
            $newMid += $increaseMid;
            $remainingDifference -= $increaseMid;
        }

        // 3. Adjust Final (Max 40)
        if ($remainingDifference > 0) {
            $maxIncreaseFinal = self::FINAL_MAX - $newFinal;
            $increaseFinal = min($remainingDifference, $maxIncreaseFinal);
            $newFinal += $increaseFinal;
        }

    } else {
        // NEGATIVE ADJUSTMENT: Reduce CW, then Mid, then Final, down to 0

        $remainingReduction = abs($remainingDifference);

        // 1. Reduce CW (Min 0)
        $maxReductionCW = $newCW;
        $reductionCW = min($remainingReduction, $maxReductionCW);
        $newCW -= $reductionCW;
        $remainingReduction -= $reductionCW;

        // 2. Reduce Midterm (Min 0)
        if ($remainingReduction > 0) {
            $maxReductionMid = $newMid;
            $reductionMid = min($remainingReduction, $maxReductionMid);
            $newMid -= $reductionMid;
            $remainingReduction -= $reductionMid;
        }

        // 3. Reduce Final (Min 0)
        if ($remainingReduction > 0) {
            $maxReductionFinal = $newFinal;
            $reductionFinal = min($remainingReduction, $maxReductionFinal);
            $newFinal -= $reductionFinal;
        }
    }

    // --- 7. Calculate the *Actual* New Total based on Adjusted components ---
    $newCW = floatval($newCW);
    $newMid = floatval($newMid);
    $newFinal = floatval($newFinal);
    $newCalculatedTotal = $newCW + $newMid + $newFinal;

    // --- 8. Ensure minimum passing threshold (don't drop below 40 if previously >= 40) ---
    if ($oldTotal >= 40 && $newCalculatedTotal < 40) {
        $requiredIncrease = 40 - $newCalculatedTotal;

        // Apply the increase in the same order: CW → Mid → Final
        $maxIncreaseCW = self::CW_MAX - $newCW;
        $increaseCW = min($requiredIncrease, $maxIncreaseCW);
        $newCW += $increaseCW;
        $requiredIncrease -= $increaseCW;

        if ($requiredIncrease > 0) {
            $maxIncreaseMid = self::MID_MAX - $newMid;
            $increaseMid = min($requiredIncrease, $maxIncreaseMid);
            $newMid += $increaseMid;
            $requiredIncrease -= $increaseMid;
        }

        if ($requiredIncrease > 0) {
            $maxIncreaseFinal = self::FINAL_MAX - $newFinal;
            $increaseFinal = min($requiredIncrease, $maxIncreaseFinal);
            $newFinal += $increaseFinal;
        }

        $newCalculatedTotal = $newCW + $newMid + $newFinal;

        // Highlight the row in light yellow
        $sheet->getStyle("A{$i}:H{$i}")
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFFF99');
    }
    }
    // --- 9. Output data to spreadsheet ---
    $sheet->setCellValue("A$i", $student['index']);
    $sheet->setCellValue("B$i", $student['name']);
    $sheet->setCellValue("C$i", ceil($newCW));
    $sheet->setCellValue("D$i", ceil($newMid));
    $sheet->setCellValue("E$i", ceil($newFinal));
    $sheet->setCellValue("F$i", ceil($newCalculatedTotal)); // New Total (Adjusted CW + Mid + Final)
    $sheet->setCellValue("G$i", round($oldTotal, 2));
    $sheet->setCellValue("H$i", round($z, 4));

    $i++;
}

//-----------------------------empty students table----------------------//
        $emptyStudents = session('empty_or_zero_students', []);
$emptyCount = count($emptyStudents);

if ($emptyCount > 0) {
    // Leave one blank line, then start a new table
    $startRow = $lastRow + 2;
    $sheet->setCellValue("A{$startRow}", "Students with Missing or Zero Grades ({$emptyCount})");
    $sheet->getStyle("A{$startRow}")->getFont()->setBold(true);
    $sheet->mergeCells("A{$startRow}:H{$startRow}");

    $headerRow = $startRow + 1;
    $sheet->fromArray(
        [['Index', 'Name', 'CW', 'Midterm', 'Final', 'Total']],
        NULL,
        "A{$headerRow}"
    );
    $sheet->getStyle("A{$headerRow}:F{$headerRow}")->getFont()->setBold(true);

    $dataStart = $headerRow + 1;
    foreach ($emptyStudents as $student) {
        $sheet->fromArray([
            [
                $student['index'],
                $student['name'],
                $student['cw'],
                $student['mid'],
                $student['final'],
                $student['total'],
            ]
        ], NULL, "A{$dataStart}");
        $dataStart++;
    }

    // Add borders to the whole table
    $lastDataRow = $dataStart - 1;
    $sheet->getStyle("A{$headerRow}:F{$lastDataRow}")
        ->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
}




        // Auto-size columns for better readability
        foreach (range('A', 'H') as $col) { 
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        // Also auto-size the stats columns K and L
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);



$sheet->getPageSetup()
    ->setPaperSize(PageSetup::PAPERSIZE_A4)
    ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
    ->setFitToWidth(1)    // Fit to 1 page wide
    ->setFitToHeight(0);  // Unlimited height (auto fit)

$sheet->getPageMargins()->setTop(0.5);
$sheet->getPageMargins()->setBottom(0.5);
$sheet->getPageMargins()->setLeft(0.5);
$sheet->getPageMargins()->setRight(0.5);

        $fileName = "{$originalFileName}-zscore-adjusted-components.xlsx";
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }


/**
* Downloads the adjusted marksheet data as a PDF document.
*/
public function downloadPdf(Request $request)
{
    $userName = Auth::user()->name;
    $students = session('students', []);
    $emptyOrZeroStudents = session('empty_or_zero_students', []);
    $originalFileName = session('original_filename', 'marksheet');
    $adminRows = session('admin_rows', []);

    // Capture chart image from the request
    $chartImageBase64 = $request->input('chart_image');

    if (empty($students)) {
        return back()->withErrors(['error' => 'No student data found in session. Please upload again.']);
    }

    // --- Data Calculations ---
    $totals = array_column($students, 'total');
    $count = count($totals);

    if ($count === 0) {
        return back()->withErrors(['error' => 'No students found for calculation.']);
    }

    $mean = array_sum($totals) / $count;
    $variance = array_sum(array_map(fn($x) => pow($x - $mean, 2), $totals)) / (($count > 1) ? $count - 1 : 1);
    $stdDev = sqrt($variance);
    $highest = max($totals);
    $lowest = min($totals);
 

    // ... in downloadPdf method ...

// 🎯 PRIORITY 1: Get values from the request (submitted hidden fields)
$requestTargetMean = $request->input('targetMean');
$requestTargetStdDev = $request->input('targetStdDev');

if (is_numeric($requestTargetMean) && is_numeric($requestTargetStdDev)) {
    // Use the values submitted from the form (the ones the user last set on the UI)
    $targetMean = floatval($requestTargetMean);
    $targetStdDev = floatval($requestTargetStdDev);
} else {
    // PRIORITY 2: Fallback to the session value (set via AJAX)
    $targetMean = session('targetMean');
    $targetStdDev = session('targetStdDev');

    // PRIORITY 3: Fallback to calculated defaults if session is also empty
    if (!is_numeric($targetMean) || !is_numeric($targetStdDev)) {
        $targetMean = ($highest + $lowest) / 2;
        $targetStdDev = ($highest - $lowest > 0) ? ($highest - $lowest) / 4 : 0;
    }
}

    $adjustedStudents = [];

    foreach ($students as $student) {
         $highlightRow = false; 

        $oldTotal = $student['total'];
        
    if ($oldTotal < 40) {
        // Keep original grades as is, no adjustment or highlighting
        $newCW = $student['cw'];
        $newMid = $student['mid'];
        $newFinal = $student['final'];
        $newCalculatedTotal = $newCW + $newMid + $newFinal;
        $z = 0;
    }else{
        $z = ($stdDev > 0) ? ($oldTotal - $mean) / $stdDev : 0;
        $targetNewTotal = $targetMean + ($targetStdDev * $z);
        $targetNewTotal = min(100, max(0, $targetNewTotal));

        $difference = $targetNewTotal - $oldTotal;

        // Initialize new scores with current scores
        $newCW = $student['cw'];
        $newMid = $student['mid'];
        $newFinal = $student['final'];
        $remainingDifference = $difference;

        if ($remainingDifference >= 0) {
            // POSITIVE ADJUSTMENT
            $maxIncreaseCW = self::CW_MAX - $newCW;
            $increaseCW = min($remainingDifference, $maxIncreaseCW);
            $newCW += $increaseCW;
            $remainingDifference -= $increaseCW;

            if ($remainingDifference > 0) {
                $maxIncreaseMid = self::MID_MAX - $newMid;
                $increaseMid = min($remainingDifference, $maxIncreaseMid);
                $newMid += $increaseMid;
                $remainingDifference -= $increaseMid;
            }

            if ($remainingDifference > 0) {
                $maxIncreaseFinal = self::FINAL_MAX - $newFinal;
                $increaseFinal = min($remainingDifference, $maxIncreaseFinal);
                $newFinal += $increaseFinal;
            }
        } else {
            // NEGATIVE ADJUSTMENT
            $remainingReduction = abs($remainingDifference);

            $maxReductionCW = $newCW;
            $reductionCW = min($remainingReduction, $maxReductionCW);
            $newCW -= $reductionCW;
            $remainingReduction -= $reductionCW;

            if ($remainingReduction > 0) {
                $maxReductionMid = $newMid;
                $reductionMid = min($remainingReduction, $maxReductionMid);
                $newMid -= $reductionMid;
                $remainingReduction -= $reductionMid;
            }

            if ($remainingReduction > 0) {
                $maxReductionFinal = $newFinal;
                $reductionFinal = min($remainingReduction, $maxReductionFinal);
                $newFinal -= $reductionFinal;
            }
        }

      


        // Calculate total
        $newCW = floatval($newCW);
        $newMid = floatval($newMid);
        $newFinal = floatval($newFinal);
        $newCalculatedTotal = $newCW + $newMid + $newFinal;

        // --- Ensure minimum total of 40 if original >= 40 ---
        if ($oldTotal >= 40 && $newCalculatedTotal < 40) {
            $requiredIncrease = 40 - $newCalculatedTotal;

            $maxIncreaseCW = self::CW_MAX - $newCW;
            $increaseCW = min($requiredIncrease, $maxIncreaseCW);
            $newCW += $increaseCW;
            $requiredIncrease -= $increaseCW;

            if ($requiredIncrease > 0) {
                $maxIncreaseMid = self::MID_MAX - $newMid;
                $increaseMid = min($requiredIncrease, $maxIncreaseMid);
                $newMid += $increaseMid;
                $requiredIncrease -= $increaseMid;
            }

            if ($requiredIncrease > 0) {
                $maxIncreaseFinal = self::FINAL_MAX - $newFinal;
                $increaseFinal = min($requiredIncrease, $maxIncreaseFinal);
                $newFinal += $increaseFinal;
            }
  $highlightRow = true;
            $newCalculatedTotal = $newCW + $newMid + $newFinal;
        }

        
    }
    $adjustedStudents[] = [
            'index' => $student['index'],
            'name' => $student['name'],
            'cw' => ceil($newCW),
            'mid' => ceil($newMid),
            'final' => ceil($newFinal),
            'newTotal' => ceil($newCalculatedTotal),
            'originalTotal' => round($oldTotal, 2),
            'zScore' => round($z, 4),
            'highlight' => $highlightRow,
        ];
}

$adjustedTotals = array_column($adjustedStudents, 'newTotal');
$adjustedCount = count($adjustedTotals);

// Calculate New Mean and Std Dev from Adjusted Totals
$newMean = ($adjustedCount > 0) ? array_sum($adjustedTotals) / $adjustedCount : 0;
$newVariance = ($adjustedCount > 1) ? array_sum(array_map(fn($x) => pow($x - $newMean, 2), $adjustedTotals)) / ($adjustedCount - 1) : 0;
$newStdDev = sqrt($newVariance);
$newHighest = ($adjustedCount > 0) ? max($adjustedTotals) : 0;
$newLowest = ($adjustedCount > 0) ? min($adjustedTotals) : 0;

// Calculate Highest Positive and Negative Adjustment
$highestPositive = 0.0; // Use float initialization
$highestNegative = 0.0; 
$i = 0; 

foreach ($students as $student) {
    if (!isset($adjustedStudents[$i])) {
        $i++;
        continue; 
    }
    
    // Original score is used for difference calculation
    $originalScore = $student['total']; 
    $adjustedScore = $adjustedStudents[$i]['newTotal']; 

    $adjustment = $adjustedScore - $originalScore;

    if ($adjustment > $highestPositive) {
        $highestPositive = $adjustment;
    }
    if ($adjustment < $highestNegative) {
        $highestNegative = $adjustment;
    }
    
    $i++; 
}
    

    // --- HTML for PDF ---
    $html = '<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Adjusted Marksheet - ' . $originalFileName . '</title>
<style>
body { font-family: sans-serif; }
.chart-page {
    text-align: center;
    height: 95vh;
    
}
    .highlight-row {
    background-color: #f8d7da; /* light red background */
    color: #721c24;            /* dark red text */
    font-weight: bold;
}

.chart-page img {
    max-width: 95%;
    margin-right:10px;
    border: 1px solid #ccc;
    height: 250px;
}
.header { text-align: center; margin-bottom: 20px; }
.admin-info { margin-bottom: 20px; font-size: 10pt; }
.admin-info p { margin: 2px 0; }
table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
th, td { border: 1px solid #000; padding: 6px 8px; text-align: left; font-size: 9pt; }
th { background-color: #f2f2f2; font-weight: bold; }
.stats-table { width: auto; float: right; margin-left: 20px; border: 2px solid #000; }
.stats-table th, .stats-table td { border: 1px solid #000; padding: 4px 8px; }
.new-total { background-color: #D9EAD3; }
.original-total { background-color: #FFF4CCCC; }
.clear { clear: both; }
.admin-detail-table {
    width: 60%; 
    border-collapse: collapse;
    margin-bottom: 10px;
    
    /* 1. Center the table (Block Element Centering) */
    margin-left: auto;
    margin-right: auto;
    
    /* 2. Add border to the whole table */
    border: 1px solid #000; /* Add a thin black border around the entire table */
}

.admin-detail-table td {
    /* 3. Add border to individual cells (reinstating borders) */
    border: 1px solid #ccc; /* Add thin light gray borders to separate cells */
    
    padding: 4px 8px; /* Increased padding slightly for better look */
    font-size: 10pt;
    vertical-align: top;
}

.admin-detail-table .label {
    font-weight: bold;
    width: 30%; 
    color: #555;
    text-align: left; 
    padding-right: 15px;
}

/* Style for main headers (University, Mark Sheet Template) */
.admin-detail-table .main-header {
    font-size: 14pt;
    font-weight: bold;
    padding-top: 5px;
}
    .analysis-table th, 
    .analysis-table td {
     
        text-align: center;
    }
</style>
</head>
<body>';

    // Chart Page
   

    // Header
    $html .= '<div class="header">
        <h1>Adjusted Marksheet: ' . $originalFileName . '</h1>
        <p>Z-Score Normalization Applied and Distributed Across Components</p>
    </div>';
    $html .= '<p style="text-align: center; font-size: 11pt; margin-top: -10px; margin-bottom: 20px;">
    Adjusted By: ' . htmlspecialchars($userName) . '
</p>';
   // Find the location where you were generating the Admin Info HTML (around line 260)

// Find the location where you were generating the Admin Info HTML
// Find the location where you were generating the Admin Info HTML

// START NEW ADMIN INFO TABLE
$html .= '<table class="admin-detail-table">';

foreach ($adminRows as $row) {
    $fullString = implode(' | ', array_filter($row));
    
    // Attempt to split the string at the first pipe '|'
    $parts = explode(' | ', $fullString, 2);
    
    $label = trim($parts[0]);
    $value = isset($parts[1]) ? trim($parts[1]) : '';

    $html .= '<tr>';
    
      
      // Loop through  labels/headers
if ($label === 'Marks Sheet Template') {
    // Skip this row entirely
    continue; 
}

if ($label === 'Future University') {
    
    $html .= '<tr>';
    $html .= '<td colspan="2" class="main-header" style="text-align: center; font-weight: bold; font-size: 18px;">' . htmlspecialchars($label) . '</td>';
    $html .= '</tr>';
    continue;
}
    // --- 2. All Other Data (Display label and value/Not Stated) ---
    
    // Define the value for display: either the actual value or "Not Stated"
    $displayValue = !empty($value) ? htmlspecialchars($value) : '<span style="color: #888;">Not Stated</span>';
    
    // Output the label in the first column and the value/placeholder in the second
    $html .= '<td class="label">' . htmlspecialchars($label) . ':</td>';
    $html .= '<td>' . $displayValue . '</td>';
    
    $html .= '</tr>';
}

$html .= '</table>';
// END NEW ADMIN INFO TABLE
 
 // Stats & Admin info// Stats & Admin info


if ($chartImageBase64) {
        $html .= '<div class="chart-page" style="width: 60%; float: left; padding-right: 10px;">';
        // $html .= '<h1>Score Distribution Analysis</h1>';
        // $html .= '<h2>' . $originalFileName . '</h2>';
        // $html .= '<p>This chart compares the Original and Adjusted (Z-Score) distributions.</p>';
        $html .= '<img src="' . $chartImageBase64 . '" alt="Score Distribution Chart">';
        $html .= '</div>';
    }
    // START: New Detailed Analysis Table (Replaces .stats-table)
$html .= '<div style="width: 40%; float: right;">';
$html .= '<table class="analysis-table" style="width: 95%; float: right; margin-left: 20px; border: 2px solid #000; page-break-inside: avoid;">
        <thead>
            <tr>
                <td>-</td>
                <td style="background-color: #EC8305; color: white;">Original</td>
                <td style="background-color: #EC8305; color: white;">Adjusted</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th style="background-color: #f2f2f2;">Mean</th> 
                <td>' . round($mean, 2) . '</td>
                <td>' . round($newMean, 2) . '</td>
            </tr>
            <tr>
                <th style="background-color: #f2f2f2;">Std Dev</th>
                <td>' . round($stdDev, 2) . '</td>
                <td>' . round($newStdDev, 2) . '</td>
            </tr>
            <tr>
                <th style="background-color: #f2f2f2;">H-Grade</th>
                <td>' . round($highest, 2) . '</td>
                <td>' . round($newHighest, 2) . '</td>
            </tr>
            <tr>
                <th style="background-color: #f2f2f2;">L-Grade</th>
                <td>' . round($lowest, 2) . '</td>
                <td>' . round($newLowest, 2) . '</td>
            </tr>
            <tr>
                <th style="background-color: #f2f2f2;">Target Mean</th>
                <td>' . round($targetMean, 2) . '</td>
                <td>' . round($targetMean, 2) . '</td>
            </tr>
            <tr>
                <th style="background-color: #f2f2f2;">Target Std Dev</th>
                <td>' . round($targetStdDev, 2) . '</td>
                <td>' . round($targetStdDev, 2) . '</td>
            </tr>
            <tr>
                <th style="background-color: #f2f2f2;">Highest +</th>
                <td colspan="2">' . round($highestPositive, 2) . '</td>
               
            </tr>
            <tr>
                <th style="background-color: #f2f2f2;">Highest -</th>
                <td colspan="2">' . round(abs($highestNegative), 2) . '</td>
                
            </tr>
        </tbody>
    </table>
    </div>';
    // END: New Detailed Analysis Table
 $html .=   '</div><div class="clear"></div>';

    // Student Table
    $html .= '<h2>Student Results (' . count($adjustedStudents) . ' Students)</h2>';
    $html .= '<table>
        <thead>
            <tr>
                <th>Index</th>
                <th>Name</th>
                <th>CW (Adj)</th>
                <th>Midterm (Adj)</th>
                <th>Final (Adj)</th>
                <th class="new-total">New Total</th>
                <th class="original-total">Original Total</th>
                <th>Z-Score</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($adjustedStudents as $student) {
         $rowClass = $student['highlight'] ? 'highlight-row' : '';
        $html .= '<tr class="' . $rowClass . '">
            <td>' . htmlspecialchars($student['index']) . '</td>
            <td>' . htmlspecialchars($student['name']) . '</td>
            <td>' . $student['cw'] . '</td>
            <td>' . $student['mid'] . '</td>
            <td>' . $student['final'] . '</td>
            <td class="new-total">' . $student['newTotal'] . '</td>
            <td class="original-total">' . $student['originalTotal'] . '</td>
            <td>' . $student['zScore'] . '</td>
        </tr>';
    }

    $html .= '</tbody></table>';

    // Empty or Zero Students Table
    if (!empty($emptyOrZeroStudents)) {
        $html .= '<h2>Students with Missing or Zero Grades (' . count($emptyOrZeroStudents) . ' Students)</h2>';
        $html .= '<table>
            <thead>
                <tr>
                    <th>Index</th>
                    <th>Name</th>
                    <th>CW</th>
                    <th>Midterm</th>
                    <th>Final</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>';
        foreach ($emptyOrZeroStudents as $student) {
            $html .= '<tr>
                <td>' . htmlspecialchars($student['index']) . '</td>
                <td>' . htmlspecialchars($student['name']) . '</td>
                <td>' . htmlspecialchars($student['cw']) . '</td>
                <td>' . htmlspecialchars($student['mid']) . '</td>
                <td>' . htmlspecialchars($student['final']) . '</td>
                <td>' . htmlspecialchars($student['total']) . '</td>
            </tr>';
        }
        $html .= '</tbody></table>';
    }

    $html .= '</body></html>';

    $pdf = Pdf::loadHTML($html);
    return $pdf->download("{$originalFileName}-zscore-adjusted.pdf");
}

/**
 * Downloads the ORIGINAL marksheet data and chart as a PDF document.
 * The Z-Score and New Total columns are included but left empty.
 */
public function downloadOriginal(Request $request)
{
    $students = session('students', []);
    $emptyOrZeroStudents = session('empty_or_zero_students', []);

    $originalFileName = session('original_filename', 'marksheet');
    $adminRows = session('admin_rows', []);

    // Capture chart image from the request
    $chartImageBase64 = $request->input('chart_image');

    if (empty($students)) {
        return back()->withErrors(['error' => 'No student data found in session. Please upload again.']);
    }

    // --- Data Calculations (Only need totals for stats calculation) ---
    $totals = array_column($students, 'total');
    $count = count($totals);

    if ($count === 0) {
        return back()->withErrors(['error' => 'No students found for statistics.']);
    }

    $mean = array_sum($totals) / $count;
    $variance = array_sum(array_map(fn($x) => pow($x - $mean, 2), $totals)) / (($count > 1) ? $count - 1 : 1);
    $stdDev = sqrt($variance);
    $highest = max($totals);
    $lowest = min($totals);
    $targetMean = ($highest + $lowest) / 2;
    $targetStdDev = ($highest - $lowest > 0) ? ($highest - $lowest) / 4 : 0;
    
    // --- HTML Generation for PDF ---
    $html = '<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Original Marksheet - ' . $originalFileName . '</title>
<style>
body { font-family: sans-serif; }
.chart-page {
    text-align: center;
    height: 95vh;
}
.highlight-row {
    background-color: #f8d7da;
    color: #721c24;
    font-weight: bold;
}
.chart-page img {
    max-width: 95%;
    margin-right:10px;
    border: 1px solid #ccc;
    height: 250px;
}
.header { text-align: center; margin-bottom: 20px; }
.admin-info { margin-bottom: 20px; font-size: 10pt; }
.admin-info p { margin: 2px 0; }
table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
th, td { border: 1px solid #000; padding: 6px 8px; text-align: left; font-size: 9pt; }
th { background-color: #f2f2f2; font-weight: bold; }
.stats-table { width: auto; float: right; margin-left: 20px; border: 2px solid #000; }
.stats-table th, .stats-table td { border: 1px solid #000; padding: 4px 8px; }
.new-total { background-color: #D9EAD3; }
.original-total { background-color: #FFF4CCCC; }
.clear { clear: both; }
.admin-detail-table {
    width: 60%; 
    border-collapse: collapse;
    margin-bottom: 10px;
    margin-left: auto;
    margin-right: auto;
    border: 1px solid #000;
}
.admin-detail-table td {
    border: 1px solid #ccc;
    padding: 4px 8px;
    font-size: 10pt;
    vertical-align: top;
}
.admin-detail-table .label {
    font-weight: bold;
    width: 30%; 
    color: #555;
    text-align: left; 
    padding-right: 15px;
}
.admin-detail-table .main-header {
    font-size: 14pt;
    font-weight: bold;
    padding-top: 5px;
}
.analysis-table th, 
.analysis-table td {
    text-align: center;
}
</style>
</head>
<body>';
  $html .= '<div class="header">
        <h1>Original Marksheet: ' . $originalFileName . '</h1>
        <p>Raw student scores as loaded from the file</p>
    </div>';

    // START ADMIN INFO TABLE
    $html .= '<table class="admin-detail-table">';

    foreach ($adminRows as $row) {
        $fullString = implode(' | ', array_filter($row));
        $parts = explode(' | ', $fullString, 2);
        
        $label = trim($parts[0]);
        $value = isset($parts[1]) ? trim($parts[1]) : '';

        $html .= '<tr>';
        
    
      // Loop through  labels/headers
if ($label === 'Marks Sheet Template') {
    // Skip this row entirely
    continue; 
}

if ($label === 'Future University') {
    
    $html .= '<tr>';
    $html .= '<td colspan="2" class="main-header" style="text-align: center; font-weight: bold; font-size: 18px;">' . htmlspecialchars($label) . '</td>';
    $html .= '</tr>';
    continue;
}
        // All Other Data (Display label and value/Not Stated)
        $displayValue = !empty($value) ? htmlspecialchars($value) : '<span style="color: #888;">Not Stated</span>';
        
        $html .= '<td class="label">' . htmlspecialchars($label) . ':</td>';
        $html .= '<td>' . $displayValue . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';
    // END ADMIN INFO TABLE

    // Chart and Stats side by side (60% chart left, 40% stats right)
    if ($chartImageBase64) {
        $html .= '<div class="chart-page" style="width: 60%; float: left; padding-right: 10px;">';
        $html .= '<img src="' . $chartImageBase64 . '" alt="Original Score Distribution Chart">';
        $html .= '</div>';
    }

    // Stats Table (Right side)
    $html .= '<div style="width: 40%; float: right;">';
    $html .= '<table class="analysis-table" style="width: 95%; float: right; margin-left: 20px; border: 2px solid #000; page-break-inside: avoid;">
        <thead>
            <tr>
                <th style="background-color: #EC8305; color: white;">Statistic</th>
                <th style="background-color: #EC8305; color: white;">Value</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th style="background-color: #f2f2f2;">Mean</th> 
                <td>' . round($mean, 2) . '</td>
            </tr>
            <tr>
                <th style="background-color: #f2f2f2;">Std Dev</th>
                <td>' . round($stdDev, 2) . '</td>
            </tr>
            <tr>
                <th style="background-color: #f2f2f2;">Highest Grade</th>
                <td>' . round($highest, 2) . '</td>
            </tr>
            <tr>
                <th style="background-color: #f2f2f2;">Lowest Grade</th>
                <td>' . round($lowest, 2) . '</td>
            </tr>
            <tr>
                <th style="background-color: #f2f2f2;">Target Mean</th>
                <td>' . round($targetMean, 2) . '</td>
            </tr>
            <tr>
                <th style="background-color: #f2f2f2;">Target Std Dev</th>
                <td>' . round($targetStdDev, 2) . '</td>
            </tr>
        </tbody>
    </table>
    </div>';
    $html .= '</div><div class="clear"></div>';
    // Student Data Table
    $html .= '<h2>Original Student Results (' . count($students) . ' Students)</h2>';
    $html .= '<table>
<thead>
<tr>
<th>Index</th>
<th>Name</th>
<th>CW (Original)</th>
<th>Midterm (Original)</th>
<th>Final (Original)</th>
<th class="original-total">Original Total</th>

</tr>
</thead>
<tbody>';

    foreach ($students as $student) {
        $html .= '<tr>
<td>' . $student['index'] . '</td>
<td>' . $student['name'] . '</td>
<td>' . ceil($student['cw']) . '</td>
<td>' . ceil($student['mid']) . '</td>
<td>' . ceil($student['final']) . '</td>
<td class="original-total">' . ceil($student['total']) . '</td>
</tr>';
    }

    $html .= '</tbody></table>';

if (!empty($emptyOrZeroStudents)) {
        $html .= '<h2>Students with Missing or Zero Grades (' . count($emptyOrZeroStudents) . ' Students)</h2>';
        $html .= '<table>
            <thead>
                <tr>
                    <th>Index</th>
                    <th>Name</th>
                    <th>CW</th>
                    <th>Midterm</th>
                    <th>Final</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($emptyOrZeroStudents as $student) {
            $html .= '<tr>
                <td>' . htmlspecialchars($student['index']) . '</td>
                <td>' . htmlspecialchars($student['name']) . '</td>
                <td>' . htmlspecialchars($student['cw']) . '</td>
                <td>' . htmlspecialchars($student['mid']) . '</td>
                <td>' . htmlspecialchars($student['final']) . '</td>
                <td>' . htmlspecialchars($student['total']) . '</td>
            </tr>';
        }

        $html .= '</tbody></table>';
    }

    $html .= '</body></html>';

 
    $pdf = Pdf::loadHTML($html);

    // Download file with a clear name
    return $pdf->download("{$originalFileName}-original-data.pdf");

}





}