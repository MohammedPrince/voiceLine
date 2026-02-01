<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marksheet Upload & Analysis</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
    body {
        font-family: "Poppins", sans-serif;
        color: #333;
        margin: 0;
        padding: 0;
    }

    h3 {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
        margin-right: 50px;
font-size:20px;

        color: #EC8305;
    }

    form {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 30px auto;
        gap: 10px;
    }

    input[type="file"] {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    button {
        background-color: #EC8305;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.2s ease-in-out;
        white-space: nowrap;

    }

    button:hover {
        background-color: #0056b3;
    }

    .chart-container {
        width: 80%;
        max-width: 900px;
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        margin-right:50px;
    }

    .info {
        text-align: center;
        margin-top: 10px;
        font-weight: 600;

    }

    .action-buttons {
        text-align: center;
        margin-top: 20px;
    }

    .action-buttons button {
        margin: 0 10px;
    }

    .submit-btn {
        width: 30%;
        border-radius: 10px;
        background-color: #ec8305;
        transition: ease-in-out 0.2s;
        padding: 10px 20px;
        border: none;
        color: white;
        font-size: 15px;
        cursor: pointer;
        margin: 0 auto;
    }



    a {
        text-decoration: none;
    }



    .logo {
        top: 1vh;
        position: fixed;
        width: 7vw;
        height: auto;
        left: 2vw;


    }

    .top-right {
        position: fixed;
        width: 16vw;
        height: auto;
        top: 0;
        right: -.1vw;
        z-index: -1;
    }

    .bottom-left {
        height: 60vh;
        position: fixed;
        width: 40vw;
        bottom: -2vh;
        left: -15vw;
        z-index: -1;
    }
    .body-flex {
        display: flex;
        flex-direction: row;
       
        width: 90%; 
        margin: 0 auto;
        justify-content: space-between; 
        align-items: flex-start;
    }

 
    .analysis-table {
        top:50%;
        width: 25%; 
        border-collapse: collapse; 
        margin: 40px auto 40px 0; 
        font-size: 0.9em; 
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        background:white; 
       
    }

    .analysis-table th, 
    .analysis-table td {
        border: 1px solid #ddd; 
        padding: 8px; 
        text-align: center;
    }

    .analysis-table thead th {
        background-color: #EC8305;
        color: white;
        text-align: center;
        padding: 10px;
        font-size: 1em;
    }
    
    .analysis-table th:not([colspan]) {
        background-color: #f2f2f2; 
        font-weight: 600;
    }

    .analysis-table tbody tr:hover {
        background-color: #f9f9f9;
    }
    .uploadui{
        width: 90%;
    }
  .download-forms-container {
    display: flex;        
    flex-direction: row;    
    gap: 10px;    
justify-self:center;       
    align-items: center;      
    flex-wrap: wrap;       
    margin-top:2vh;  
    margin-bottom:2vh;  
}


.download-forms-container form {
    margin: 0;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropbtn {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 22px;
  color: #333;
}

.dropdown-content {
  display: none;
  position: absolute;
  right: 0;
  background-color: #fff;
  min-width: 160px;
  box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
  border-radius: 8px;
  z-index: 1;
}

.dropdown-content a {
  color: #333;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
  transition: background 0.2s;
}

.dropdown-content a:hover {
  background-color: #f5f5f5;
}

.dropdown:hover .dropdown-content {
  display: block;
}

.profile {
  position: absolute;
  top: 15px;
  right: 20px;
}
.dropdown-content a:hover {
    background-color: #f5f5f5 !important;
    color: #333;
}
/* Specific fix for the profile button background */
.dropbtn, .dropbtn:hover, .dropbtn:focus {
    background-color: transparent !important;
    outline: none !important;
    box-shadow: none !important;
}




    .download-forms-container button {
   
        width: 100%; 
    }
    @media (max-width: 768px) {
        
        h3 {
            margin-right: 0;
        }

    
        .body-flex {
            flex-direction: column;
            width: 95%; /* Use more available width */
            align-items: center;
            margin-top:-2opx;
        }
        
      
        .chart-container {
            width: 95%; 
            max-width: none;
            margin: 10px auto; 
        }
        
        .analysis-table {
               width: 45%; 
                margin: 10px auto; 
            font-size: 0.8em; /* Smaller font on small screens */
            order: 2; /* Put the table below the chart in the visual flow if needed, but here chart comes after table in HTML, so we'll just let it flow naturally or reverse as needed. */
            margin-top: 20px;
        }

        /* Adjust input/button layout for mobile form */
        form {
            flex-direction: column;
            gap: 15px;
        }
        
        input[type="file"], form button, .submit-btn {
            width: 80%;
            max-width: 300px;
        }
        
        /* Stack download buttons */
        .download-forms-container {
            flex-direction: column;
            align-items: center;
        }
        .download-forms-container button {
            width: 90%;
            max-width: 350px;
        }

        /* Hide the decorative/fixed position elements */
        .logo, .top-right, .bottom-left {
            display: none;
        }
        
        /* Adjust slider container layout for small screens */
        .chart-container > div:first-child {
            flex-direction: column; /* Stack the mean and std dev sliders */
            align-items: center;
        }
        
        .chart-container > div:first-child > div {
            width: 90%; /* Give slider inputs more space */
        }
        .download-forms-container {
            flex-direction: column;
            align-items: center; /* Center the stacked forms */
            gap: 15px; /* Increase gap between stacked buttons */
        }
    }

    </style>
</head>

<body>

     <button type="button" onclick="window.location='{{ route('dashboard') }}'"
        style="background: none; border: none; padding: 0; cursor: pointer;">
        <img src="{{ asset('assets/logowithname.svg') }}" class="logo" alt="logo" draggable="false">
    </button>


    <!-- Decorative Images -->
    <img src="{{ asset('assets/bottomleft.svg') }}" class="bottom-left" alt="bottomleft" draggable="false">
    <img src="{{ asset('assets/topright.svg') }}" class="top-right" alt="topright" draggable="false">

    <!-- User Profile Dropdown -->
    <div class="profile">
        <div class="dropdown">
            <button class="dropbtn">
                <i class="fa-solid fa-circle-user" style="color: white; font-size: 38px;"></i>
            </button>
            <div class="dropdown-content" id="profile-dropdown-content"
                style="position: absolute; right: 0; min-width: 120px; max-width: 180px;max-hieght">
                <a class="a" href="{{ url('/profile') }}">Profile</a>
                <a class="a" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden"
                    style="display:none;">
                    <style></style>

                    @csrf
                </form>
            </div>
        </div>
    </div>

    <h3>📊 Marksheet Upload & Student Analysis</h3>
<h3 id="filename" > Uploaded File: {{ $fileName ?? 'N/A' }}</h3>
  
        <div class="uploadui">
         <form action="{{ route('marksheet.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" id="fileInput" required>
        <button type="submit" id="uploadBtn">Upload</button>

        <div>
            <a href="{{ route('marksheet.reset') }}" class="submit-btn">Refresh</a>
            
        </div>

    </form>

    @if ($errors->any())
    <div style="color: red; text-align:center;">
        <strong>{{ $errors->first() }}</strong>
        
    </div>
    @endif
    
        </div>
    

    @isset($labels)


     



   

  <div class="body-flex">

 <div class="chart-container">

        <div style="margin-bottom: 20px; display: flex; gap: 40px; align-items: center; flex-wrap: wrap;">
            <div style="margin-bottom: 0;">
                <label style="font-weight:600;">Target Mean:</label>
                <div style="display:flex; align-items:center; gap:10px; width:400px;">
                    <span id="meanMinLabel"></span>
                    <input type="range" id="targetMeanSlider" step="0.1" style="flex:1;">
                    <span id="meanMaxLabel"></span>
                    <span id="targetMeanValue"
                        style="min-width:70px; text-align:center; font-weight:600; color:#EC8305;"></span>
                </div>
            </div>

            <div style="margin-bottom: 0;">
                <label style="font-weight:600;">Target Std Dev:</label>
                <div style="display:flex; align-items:center; gap:10px; width:400px;">
                    <span id="stdMinLabel"></span>
                    <input type="range" id="targetStdSlider" step="0.1" style="flex:1;">
                    <span id="stdMaxLabel"></span>
                    <span id="targetStdValue"
                        style="min-width:70px; text-align:center; font-weight:600; color:#EC8305;"></span>
                </div>
            </div>
        </div>


        <div class="info">
            Total Students Detected: <span style="color:#007bff;">{{ $totalStudents }}</span>
        </div>
        <div class="info" id="gradePercentages" style="margin-top:5px; font-weight:500;">
            A (≥ 90): <span id="aPercent" style="color:green;"></span> % |
            F (< 40): <span id="fPercent" style="color:red;"></span> %
        </div>

        <canvas id="marksChart"></canvas>
    </div>

<!-- ---------------------------------------------------------------------------- -->
 <table class="analysis-table">
    <thead>
        <tr>
            {{-- This header row spans 2 columns --}}
            <td>
               -
            </td>
            <td>
                Original
            </td>
            <td>
                Adjusted
            </td>
        </tr>
    </thead>
    <tbody>
        <tr>
            {{-- Metric Name Column (Header) --}}
            <th>Mean</th> 
            {{-- Value Column (Data) --}}
            <td id="originalMeanVal"></td>
            <td id="NewMeanVal"></td>
        </tr>
        <tr>
            <th>Std Dev</th>
            <td id="originalStdDevVal"></td>
            <td id="NewStdDevVal"></td>
        </tr>
        <tr>
            <th>H-Grade</th>
            <td id="highestGradeVal"></td>
             <td id="NewhighestGradeVal"></td>
        </tr>
        <tr>
            <th>L-Grade</th>
            <td id="lowestGradeVal"></td>
             <td id="NewlowestGradeVal"></td>
        </tr>
        <tr>
            <th>Target Mean</th>
            <td id="targetMeanValTable"></td>
            <td id="NewtargetMeanValTable"></td>

        </tr>
        <tr>
            <th>Target Std Dev</th>
            <td id="targetStdDevValTable"></td>
            <td id="NewtargetStdDevValTable"></td>

        </tr>
        <tr>
            <th>Highest (+) </th>
            <td colspan="2" id="highestPositiveAdded"></td>
         

        </tr><tr>
            <th>Highest (-)</th>
            <td colspan="2" id="highestNegativeAdded"></td>
           

        </tr>
    </tbody>
</table>

     </div>

   

    <div class="action-buttons">
        <button type="button" id="curveBtn">Curve Grades (Dynamic Z-Score)</button>
        <button type="button" id="resetGraphBtn" style="background-color:#6c757d;">Reset Graph</button>
    </div>

    {{-- Three Download Forms in a Flex Container --}}
  <div class="download-forms-container">
    {{-- 1. Z-Score Adjusted Excel Download --}}
    <form  style="display: none;" action="{{ route('marksheet.download') }}" method="POST" id="downloadForm">
        @csrf
        <input type="hidden" name="targetMean" id="downloadTargetMean">
        <input type="hidden" name="targetStdDev" id="downloadTargetStdDev">
        <button type="submit">Download Z-Score Adjusted Excel</button>
    </form>

    {{-- 2. Z-Score PDF Download --}}
    <form action="{{ route('marksheet.download-pdf') }}" method="POST" id="downloadPdfForm">
        @csrf
        <input type="hidden" name="chart_image" id="chartImageInput">
        <input type="hidden" name="targetMean" id="downloadPdfTargetMean">
        <input type="hidden" name="targetStdDev" id="downloadPdfTargetStdDev">
        <button type="button" id="downloadPdfBtn" style="background-color: #dc3545;">
            Download Z-Score PDF
        </button>
    </form>

    {{-- 3. Original Marksheet PDF Download --}}
    <form action="{{ route('marksheet.download-original') }}" method="POST" id="downloadOriginalPdfForm">
        @csrf
        <input type="hidden" name="marksheet_id" id="marksheetIdOriginalInput" value="{{ $marksheetId ?? '' }}">
        <input type="hidden" name="chart_image" id="chartImageOriginalInput">
        <input type="hidden" name="targetMean" id="downloadOriginalPdfTargetMean">
        <input type="hidden" name="targetStdDev" id="downloadOriginalPdfTargetStdDev">
        <button type="button" id="downloadOriginalPdfBtn" style="background-color: #007bff;">
            Download Original Marksheet PDF
        </button>
    </form>
</div>
   

    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script>
    const meanSlider = document.getElementById('targetMeanSlider');
    const stdSlider = document.getElementById('targetStdSlider');
    const meanVal = document.getElementById('targetMeanValue');
    const stdVal = document.getElementById('targetStdValue');
    // --- 1. DATA SETUP ---
    const labels = @json($labels);
    const originalTotals = @json($totals);
    const totalStudents = originalTotals.length;
const filename=@json($filename);
    let counts = @json($counts);

    function groupIntoRanges(totals) {
        const ranges = Array(10).fill(0);
        for (const score of totals) {
            const clamped = Math.min(100, Math.max(0, score));
            const index = Math.min(Math.floor(clamped / 10), 9);
            ranges[index]++;
        }
        return ranges;
    }

    const N = originalTotals.length;
    const mean = originalTotals.reduce((a, b) => a + b, 0) / N;
    const variance = originalTotals.reduce((a, b) => a + Math.pow(b - mean, 2), 0) / (N > 1 ? N - 1 : 1);
    const stdDev = Math.sqrt(variance);
    const highest = Math.max(...originalTotals);
    const lowest = Math.min(...originalTotals);
    const targetMean = (highest + lowest) / 2;
    const targetStdDev = (highest - lowest > 0) ? (highest - lowest) / 4 : 0;


    // --- NEW JAVASCRIPT: POPULATE INITIAL TABLE DATA ---
    // NOTE: These values are calculated once on load and represent the ORIGINAL data.
    document.getElementById('originalMeanVal').textContent = mean.toFixed(2);
    document.getElementById('originalStdDevVal').textContent = stdDev.toFixed(2);
    document.getElementById('highestGradeVal').textContent = highest.toFixed(2);
    document.getElementById('lowestGradeVal').textContent = lowest.toFixed(2);
    // Populate initial target values (the calculated defaults)
    document.getElementById('targetMeanValTable').textContent = targetMean.toFixed(2);
    document.getElementById('targetStdDevValTable').textContent = targetStdDev.toFixed(2);
     document.getElementById('filename').textContent = filename;
    // --- END POPULATING INITIAL TABLE DATA ---

    function calculateGradePercentages() {
        const fCount = originalTotals.filter(score => score < 40).length;
        const aCount = originalTotals.filter(score => score >= 90).length;
        const fPercent = totalStudents ? ((fCount / totalStudents) * 100).toFixed(1) : 0;
        const aPercent = totalStudents ? ((aCount / totalStudents) * 100).toFixed(1) : 0;

        document.getElementById('fPercent').textContent = fPercent;
        document.getElementById('aPercent').textContent = aPercent;
    }

    // Initial display
    calculateGradePercentages();

    function applyZScoreCurve(totals, meanOverride = targetMean, stdOverride = targetStdDev) {
        if (stdDev === 0) return totals.map(() => meanOverride);
        return totals.map(x => {
            const z = (x - mean) / stdDev;
            const newTotal = meanOverride + stdOverride * z;
            return Math.min(100, Math.max(0, newTotal));
        });
    }

    // --- 2. CHART INITIALIZATION ---
    const ctx = document.getElementById('marksChart').getContext('2d');
    const marksChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                    type: 'line',
                    label: 'Original Distribution',
                    data: counts,
                    backgroundColor: 'rgba(54, 163, 235, 0.85)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 3,
                    tension: 0.3,
                    pointRadius: 5,
                    borderDash: [5, 5],
                },
                {
                    type: 'line',
                    label: 'Dynamic Z-Score Adjusted Distribution',
                    data: new Array(counts.length).fill(null),
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    fill: false,
                    tension: 0.3,
                    borderWidth: 3,
                    pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                    pointRadius: 5
                }
            ]
        },
        options: {
            responsive: true,
            animation: false,
            plugins: {
                legend: {
                    display: true
                },
                title: {
                    display: true,
                    text: `Student Score Distribution (0–100) - Target Mean: ${targetMean.toFixed(2)}, Target StdDev: ${targetStdDev.toFixed(2)}`,
                    font: {
                        size: 14
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Score Range'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Students'
                    }
                }
            }
        }
    });

    // --- 3. CHART BUTTONS (AFTER marksChart EXISTS) ---
   // --- 3. CHART BUTTONS (AFTER marksChart EXISTS) ---
document.getElementById('curveBtn').addEventListener('click', function() {
    // 1. Calculate adjusted totals using initial target values
    // Note: It uses the initial targetMean and targetStdDev since the sliders haven't been touched yet.
    const adjustedTotals = applyZScoreCurve(originalTotals);
    const adjustedCounts = groupIntoRanges(adjustedTotals);

    // 2. Calculate the highest positive and negative adjustment
    let highestPositive = 0;
    let highestNegative = 0;

    for (let i = 0; i < originalTotals.length; i++) {
        const adjustment = adjustedTotals[i] - originalTotals[i];

        if (adjustment > highestPositive) {
            highestPositive = adjustment;
        }
        if (adjustment < highestNegative) {
            highestNegative = adjustment;
        }
    }
    
    // 3. Update Chart
    marksChart.data.datasets[1].data = adjustedCounts;
    marksChart.options.plugins.title.text =
        `Student Score Distribution (0–100) - Target Mean: ${targetMean.toFixed(2)}, Target StdDev: ${targetStdDev.toFixed(2)} (PREVIEW)`;
    marksChart.update();
    
    // 4. Update the Table Values for Adjustments
    // The table columns need to be updated with the results of this adjustment.
    
    // Calculate New Statistics (from the clamped totals) for the table
    const N_adj = adjustedTotals.length;
    const newOriginalMean = adjustedTotals.reduce((a, b) => a + b, 0) / N_adj;
    const newVariance = adjustedTotals.reduce((a, b) => a + Math.pow(b - newOriginalMean, 2), 0) / (N_adj > 1 ? N_adj - 1 : 1);
    const newStdDev = Math.sqrt(newVariance);
    const newHighest = Math.max(...adjustedTotals);
    const newLowest = Math.min(...adjustedTotals);
    
    document.getElementById('NewtargetMeanValTable').textContent = targetMean.toFixed(2);
    document.getElementById('NewtargetStdDevValTable').textContent = targetStdDev.toFixed(2);
    document.getElementById('NewMeanVal').textContent = newOriginalMean.toFixed(2);
    document.getElementById('NewStdDevVal').textContent = newStdDev.toFixed(2);
    document.getElementById('NewhighestGradeVal').textContent = newHighest.toFixed(2);
    document.getElementById('NewlowestGradeVal').textContent = newLowest.toFixed(2);
    
    // Update the adjustment rows
    document.getElementById('highestPositiveAdded').textContent = highestPositive.toFixed(2);
    document.getElementById('highestNegativeAdded').textContent = Math.abs(highestNegative).toFixed(2);

    // Also update A% and F% (optional, but good practice for consistency)
    const fCount = adjustedTotals.filter(score => score < 40).length;
    const aCount = adjustedTotals.filter(score => score >= 90).length;
    document.getElementById('fPercent').textContent = ((fCount / totalStudents) * 100).toFixed(1);
    document.getElementById('aPercent').textContent = ((aCount / totalStudents) * 100).toFixed(1);
});



document.getElementById('resetGraphBtn').addEventListener('click', function() {
    // 🧭 Reset chart data
    marksChart.data.datasets[1].data = new Array(counts.length).fill(null);
    marksChart.options.plugins.title.text =
        `Student Score Distribution (0–100) - Target Mean: ${baseMean.toFixed(2)}, Target StdDev: ${baseStd.toFixed(2)}`;
    marksChart.update();

    // 🎚️ Reset sliders to their base values
    meanSlider.value = baseMean.toFixed(1);
    stdSlider.value = baseStd.toFixed(1);

    // 🏷️ Reset displayed values beside sliders
    meanVal.textContent = baseMean.toFixed(1);
    stdVal.textContent = baseStd.toFixed(1);

    // 🗑️ NEW: Clear all Adjusted values and Reset Target values in the table
    document.getElementById('NewtargetMeanValTable').textContent = '';
    document.getElementById('NewtargetStdDevValTable').textContent = '';
    document.getElementById('NewMeanVal').textContent = '';
    document.getElementById('NewStdDevVal').textContent = '';
    document.getElementById('NewhighestGradeVal').textContent = '';
    document.getElementById('NewlowestGradeVal').textContent = '';
    document.getElementById('highestPositiveAdded').textContent = '';
    document.getElementById('highestNegativeAdded').textContent = '';
    
    // Also reset the percentages to the original data's percentages
    calculateGradePercentages(); 

    // Reset the "Original" Target values back to the base values (if they were changed)
    document.getElementById('targetMeanValTable').textContent = baseMean.toFixed(2);
    document.getElementById('targetStdDevValTable').textContent = baseStd.toFixed(2);

    // 🧩 Reset hidden fields used for downloads
    document.getElementById('downloadTargetMean').value = baseMean.toFixed(2);
    document.getElementById('downloadTargetStdDev').value = baseStd.toFixed(2);
    document.getElementById('downloadPdfTargetMean').value = baseMean.toFixed(2);
    document.getElementById('downloadPdfTargetStdDev').value = baseStd.toFixed(2);
    document.getElementById('downloadOriginalPdfTargetMean').value = baseMean.toFixed(2);
    document.getElementById('downloadOriginalPdfTargetStdDev').value = baseStd.toFixed(2); // Added for completeness

    // 💾 Optionally update backend values too (optional but keeps everything consistent)
    fetch("{{ route('update-target-values') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                targetMean: baseMean,
                targetStdDev: baseStd
            })
        }).then(response => response.json())
        .then(data => console.log('Target values reset:', data))
        .catch(error => console.error('Reset error:', error));
});

    // --- 5. PDF CAPTURE ---
    function captureChartAndSubmitForm(chartInputId, formId, includeCurve) {
        let savedCurveData = null;
        let savedTitle = marksChart.options.plugins.title.text;

        if (!includeCurve) {
            const curveDataset = marksChart.data.datasets[1];
            savedCurveData = [...curveDataset.data];
            curveDataset.data = new Array(savedCurveData.length).fill(null);
            marksChart.options.plugins.title.text = savedTitle.replace(' (PREVIEW)', '');
        }

        marksChart.options.backgroundColor = 'white';
        marksChart.update();

        const chartImage = marksChart.toBase64Image();
        document.getElementById(chartInputId).value = chartImage;

        if (!includeCurve && savedCurveData) {
            marksChart.data.datasets[1].data = savedCurveData;
            marksChart.options.plugins.title.text = savedTitle;
        }

        marksChart.options.backgroundColor = null;
        marksChart.update();

        document.getElementById(formId).submit();
    }


    function syncHiddenInputs() {
        const newMean = parseFloat(meanSlider.value);
        const newStd = parseFloat(stdSlider.value);

        document.getElementById('downloadTargetMean').value = newMean.toFixed(2);
        document.getElementById('downloadTargetStdDev').value = newStd.toFixed(2);
        document.getElementById('downloadPdfTargetMean').value = newMean.toFixed(2);
        document.getElementById('downloadPdfTargetStdDev').value = newStd.toFixed(2);
        document.getElementById('downloadOriginalPdfTargetMean').value = newMean.toFixed(2);
        document.getElementById('downloadOriginalPdfTargetStdDev').value = newStd.toFixed(2);
    }

    // --- 6. DOWNLOAD BUTTONS ---
    document.getElementById('downloadPdfBtn').addEventListener('click', function() {
        syncHiddenInputs(); // ✅ make sure latest slider values go to hidden inputs

        // ensure curve exists before capture
        if (marksChart.data.datasets[1].data.every(val => val === null)) {
            document.getElementById('curveBtn').click();
        }

        captureChartAndSubmitForm('chartImageInput', 'downloadPdfForm', true);
    });


    document.getElementById('downloadOriginalPdfBtn').addEventListener('click', function() {
        syncHiddenInputs(); // ✅ important
        captureChartAndSubmitForm('chartImageOriginalInput', 'downloadOriginalPdfForm', false);
    });


    document.querySelector('#downloadForm button').addEventListener('click', function() {
        syncHiddenInputs(); // ✅ make sure latest values are included
    });


    // --- LIVE SLIDER SETUP (ACCURATE VERSION) ---
// --- LIVE SLIDER SETUP (ACCURATE VERSION) ---

    // Define base values using PHP/Blade, converting them to floats for JS
    const baseMean = parseFloat({{ round(($totals ? (max($totals) + min($totals)) / 2 : 0), 2) }});
    const baseStd = parseFloat({{ round(($totals ? (max($totals) - min($totals)) / 4 : 0), 2) }});
  


 

    // Initialize slider ranges and labels
    meanSlider.min = (baseMean - 10).toFixed(1);
    meanSlider.max = (baseMean + 10).toFixed(1);
    meanSlider.value = baseMean.toFixed(1);
    meanVal.textContent = baseMean.toFixed(1);

    stdSlider.min = Math.max(0, (baseStd - 10)).toFixed(1);
    stdSlider.max = (baseStd + 10).toFixed(1);
    stdSlider.value = baseStd.toFixed(1);
    stdVal.textContent = baseStd.toFixed(1);

    document.getElementById('meanMinLabel').textContent = meanSlider.min;
    document.getElementById('meanMaxLabel').textContent = meanSlider.max;
    document.getElementById('stdMinLabel').textContent = stdSlider.min;
    document.getElementById('stdMaxLabel').textContent = stdSlider.max;

    // --- Function to update chart LIVE when sliders move ---
function updateChartLive() {
    // 1. Get the current slider values (newTargetMean, newTargetStd)
    const newMean = parseFloat(meanSlider.value); // This is your new Target Mean
    const newStd = parseFloat(stdSlider.value); // This is your new Target Std Dev

    // 2. Perform the main calculation once.
    // Use the correct slider values: newMean and newStd
    const adjustedTotals = applyZScoreCurve(originalTotals, newMean, newStd);
    const adjustedCounts = groupIntoRanges(adjustedTotals);

    // 3. Calculate New Statistics (from the clamped totals)
    const N_adj = adjustedTotals.length;
    
    // Calculate New Mean from adjusted totals (using your name: newOriginalMean)
    const newOriginalMean = adjustedTotals.reduce((a, b) => a + b, 0) / N_adj;
    
    // Calculate New Standard Deviation
    const newVariance = adjustedTotals.reduce((a, b) => a + Math.pow(b - newOriginalMean, 2), 0) / (N_adj > 1 ? N_adj - 1 : 1);
    const newStdDev = Math.sqrt(newVariance);
    
    // Calculate New Highest and Lowest Grades
    const newHighest = Math.max(...adjustedTotals);
    const newLowest = Math.min(...adjustedTotals);
    let highestPositive = 0;
    let highestNegative = 0; // Will store the largest negative difference (e.g., -15.5)

    for (let i = 0; i < originalTotals.length; i++) {
        const originalScore = originalTotals[i];
        const adjustedScore = adjustedTotals[i]; 

        // The adjustment is the change from the original score
        const adjustment = adjustedScore - originalScore;

        if (adjustment > highestPositive) {
            highestPositive = adjustment;
        }

        // We are looking for the largest subtraction, which means the most negative adjustment
        if (adjustment < highestNegative) {
            highestNegative = adjustment;
        }
    }
    // Update Displays
    
    // Update text beside sliders
    meanVal.textContent = newMean.toFixed(1);
    stdVal.textContent = newStd.toFixed(1);

    // Update chart instantly
    marksChart.data.datasets[1].data = adjustedCounts;
    marksChart.options.plugins.title.text =
        `Student Score Distribution (0–100) - Target Mean: ${newMean.toFixed(2)}, Target StdDev: ${newStd.toFixed(2)} (LIVE)`;
    marksChart.update();
    
    // Update table values
    document.getElementById('NewtargetMeanValTable').textContent = newMean.toFixed(2);
    document.getElementById('NewtargetStdDevValTable').textContent = newStd.toFixed(2);
    document.getElementById('NewMeanVal').textContent = newOriginalMean.toFixed(2);
    document.getElementById('NewStdDevVal').textContent = newStdDev.toFixed(2);
    document.getElementById('NewhighestGradeVal').textContent = newHighest.toFixed(2);
    document.getElementById('NewlowestGradeVal').textContent = newLowest.toFixed(2);
    document.getElementById('highestPositiveAdded').textContent = highestPositive.toFixed(2);
    document.getElementById('highestNegativeAdded').textContent = Math.abs(highestNegative).toFixed(2);
    // 5. Update hidden fields for downloads
    document.getElementById('downloadTargetMean').value = newMean.toFixed(2);
    document.getElementById('downloadTargetStdDev').value = newStd.toFixed(2);
    document.getElementById('downloadPdfTargetMean').value = newMean.toFixed(2);
    document.getElementById('downloadPdfTargetStdDev').value = newStd.toFixed(2);
    document.getElementById('downloadOriginalPdfTargetMean').value = newMean.toFixed(2);
    document.getElementById('downloadOriginalPdfTargetStdDev').value = newStd.toFixed(2);

    // 6. Send updated values to the backend (live)
    fetch("{{ route('update-target-values') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            targetMean: newMean,
            targetStdDev: newStd
        })
    }).then(response => response.json())
    .then(data => console.log('Target values updated:', data))
    .catch(error => console.error('Update error:', error));

    // 7. Update A% and F% dynamically based on adjusted totals
    const fCount = adjustedTotals.filter(score => score < 40).length;
    const aCount = adjustedTotals.filter(score => score >= 91).length;
    document.getElementById('fPercent').textContent = ((fCount / totalStudents) * 100).toFixed(1);
    document.getElementById('aPercent').textContent = ((aCount / totalStudents) * 100).toFixed(1);
}




    meanSlider.addEventListener('input', updateChartLive);
    stdSlider.addEventListener('input', updateChartLive);



    document.getElementById('uploadBtn').addEventListener('click', function(e) {
        e.preventDefault(); // ⛔ Stop the default form submission

        // 🧹 Reset sliders
        const meanSlider = document.getElementById('targetMeanSlider');
        const stdSlider = document.getElementById('targetStdSlider');
        const meanValue = document.getElementById('targetMeanValue');
        const stdValue = document.getElementById('targetStdValue');

        if (meanSlider && stdSlider) {
            meanSlider.value = '';
            stdSlider.value = '';
        }
        if (meanValue) meanValue.textContent = '';
        if (stdValue) stdValue.textContent = '';

        // 🧩 Reset hidden fields
        [
            'downloadTargetMean', 'downloadTargetStdDev',
            'downloadPdfTargetMean', 'downloadPdfTargetStdDev',
            'downloadOriginalPdfTargetMean', 'downloadOriginalPdfTargetStdDev'
        ].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });

        // ✅ Now safely submit the form after clearing
        e.target.closest('form').submit();
    });
    </script>

    @endisset

</body>

</html>