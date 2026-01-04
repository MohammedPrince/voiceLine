<!-- resources/views/reports/voicecalls.blade.php -->
 
@extends('layouts.app')

@section('title', 'Call Page')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
    #mainAlert {
  position: fixed;
  top: 20px;
  right: 20px;
  min-width: 250px;
  z-index: 1055; /* above bootstrap modals */
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

</style>
<div class="reports-container mt-4">
    <ul>
        <li><a href="/reports"> General Report </a></li>
        <li><a href="/reports/calls-per-user">Detailed Report</a></li>
         <li><a href="/reports/voice-calls" class="active">Voice Calls Report</a></li>
    </ul>

 

    <h3 class="mb-3">Voice Calls Report</h3>

    <!-- فلترة -->
    <form id="filterForm" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control">
        </div>

        <div class="col-md-3">
            <label class="form-label">Ticket Number</label>
            <input type="text" name="ticket_number" class="form-control" placeholder="Enter ticket number">
        </div>

        <div class="col-md-3">
            <label class="form-label">Customer Type</label>
            <input type="text" name="customer_type" class="form-control" placeholder="Enter customer type">
        </div>

        <div class="col-md-3">
            <label class="form-label">Name</label>
            <input type="text" name="parent_name" class="form-control" placeholder="Enter name">
        </div>

 <div class="col-md-3">
            <label class="form-label">Student ID</label>
            <input type="text" name="stud_id" class="form-control" placeholder="Enter student ID">
        </div>


        <div class="col-md-3">
            <label class="form-label">Staff ID</label>
            <input type="text" name="staff_ID" class="form-control" placeholder="Enter staff ID">
        </div>

        <div class="col-md-3">
            <label class="form-label">Category</label>
 <select id="category" name="category">
    <option value="" selected></option>

    <!-- Certificates -->
    <option value="1">[Certificates] Graduates Lists</option>
    <option value="2">[Certificates] Delayed Issuance</option>
    <option value="3">[Certificates] Payment Issues</option>
    <option value="4">[Certificates] General</option>

    <!-- Finance -->
    <option value="5">[Finance] Delayed Approval</option>

    <!-- Academic -->
    <option value="6">[Academic] Delayed Approval</option>
    <option value="7">[Academic] Delayed Result</option>
    <option value="8">[Academic] Registration</option>
    <option value="9">[Academic] Verification</option>
    <option value="10">[Academic] F/z result</option>
    <option value="11">[Academic] Postgraduate</option>
    <option value="12">[Academic] remarking</option>

    <!-- E-Learning -->
    <option value="13">[E-Learning] Account Activation</option>
    <option value="14">[E-Learning] F/Z Course Enrolment</option>
    <option value="15">[E-Learning] Wrong Courses</option>

    <!-- HelpDesk -->
    <option value="16">[HelpDesk] Password Reset</option>

    <!-- General Inquiry -->
    <option value="17">[General Inquiry] New Admission</option>
    <option value="18">[General Inquiry] General</option>
    <option value="19">[General inquiry] others</option>
    <option value="20">[General inquiry] Complaint</option>

    <!-- Addmission -->
    <option value="21">[Addmission] Internal/external transfer/تجسير</option>

    <!-- CTS -->
    <option value="22">[CTS] SMOWL issues</option>
    <option value="23">[CTS] Exam Access</option>
    <option value="24">[CTS] Close ticket /OPEN</option>

    <!-- CESD -->
    <option value="25">[CESD] Index issuing</option>
</select>


        </div>
<div class="col-12 d-flex justify-content-center gap-2">
    <button type="button" id="filterBtn"
        class="btn btn-primary btn-sm"
        style="max-width:120px;">
        Search
    </button>

    <button type="reset"
        class="btn btn-secondary btn-sm"
        style="max-width:120px;">
        Reset
    </button>
</div>




    </form>
<div id="mainAlert" class="alert alert-danger d-none" role="alert"></div>
    <!-- جدول النتائج -->
   <table class="table table-bordered" id="resultsTable">
    <thead>
        <tr>
            <th>Call ID</th>
            <th>Ticket</th>
            <th>Category</th>
            <th>Final Status</th>
            <th>Created At</th>
            <th>Details</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

</div>
<!-- Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailsModalLabel">Call Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Details content will be injected here -->
        <table class="table table-sm">
          <tbody id="modalDetailsBody"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="StatusModal" tabindex="-1" aria-labelledby="StatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="StatusModalLabel" style="color: #EC8305;">Student Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 20px;">
<div id="modalAlert" class="alert alert-danger d-none" role="alert"></div>
<div id="statusLoader" class="text-center my-4">

    <div class="spinner-border text-warning" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <p class="mt-2">Loading student data...</p>
</div>


<div id="statusContent" style="display:none;">
    

       <p><b>Name:</b> <span id="studentName"> {{ session('name') }} </span></p>
                <p><b>Major:</b> <span id="studentMajor"> {{ session('name') }}</span></p>
                <p><b>Batch:</b> <span id="studentBatch"> {{ session('batch') }}</span></p>
                <p><b>Semester:</b> <span id="studentSemester"> {{ session('semester') }}</span></p>

<p>
    <!-- <b>GPA:</b>
    <span id="studentGpa">{{ session('gpa') }}</span>
    &nbsp; | &nbsp; -->

    <b>CGPA:</b>
    <span id="studentCgpa">{{ session('last_cgpa') }}</span>
    &nbsp; | &nbsp;

    <b>Status:</b>
    <span id="studentStatus">{{ session('status') }}</span>
</p>


                <!-- <p><b>Status:</b> <span id="studentStatus"> {{ session('status') }}</span></p> -->
 
                <!-- Subjects Table -->
                <div class="row mb-2">
                    <div class="col-12 mb-2">
                        <p><b>F/Z/I Subjects:</b></p>
                    </div>
                    <div class="col-12">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="color: #EC8305;">Sem</th>
                                      <th style="color: #EC8305;">Subject Code</th>
                                    <th style="color: #EC8305;">Course Name</th>
                                    <th style="color: #EC8305;">Grade</th>
                                    <th style="color: #EC8305;">Remark</th>
                                </tr>
                            </thead>
                            <tbody id="studentSubjectsTable">
                                <tr>
                                    <td colspan="4" class="text-center">No data available</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Subjects Table -->
                <div class="row mb-2">
                    <div class="col-12 mb-2">
                        <p><b>Tickets:</b></p>
                    </div>
                    <div class="col-12">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="color: #EC8305;">Ticket ID</th>
                                    <th style="color: #EC8305;">Ticket Subject</th>
                                    <th style="color: #EC8305;">URL</th>
                                    <th style="color: #EC8305;">Get</th>
                                    <th style="color: #EC8305;">Remark</th>
                                </tr>
                            </thead>
                            <tbody id="ticketsTable">
                                <tr>
                                    <td colspan="4" class="text-center">No data available</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

</div>

<!-- <script>
document.getElementById("filterBtn").addEventListener("click", function() {
    let formData = new FormData(document.getElementById("filterForm"));

    fetch("{{ route('reports.voicecalls.search') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        let tbody = document.querySelector("#resultsTable tbody");
        tbody.innerHTML = "";
        if (data.length === 0) {
            tbody.innerHTML = "<tr><td colspan='8' class='text-center'>No Data Found</td></tr>";
            return;
        }
  let rowsHtml = '';
data.forEach(row => {
    let fullData = encodeURIComponent(JSON.stringify(row));
    rowsHtml += `
        <tr>
            <td>${row.call_id ?? ''}</td>
            <td>${row.ticket_number ?? ''}</td>
            <td>${row.category ?? ''}</td>
            <td>${row.Final_Status ?? ''}</td>
            <td>${row.created_at ?? ''}</td>
            <td>
                <button class="btn btn-sm btn-info detailsBtn" data-details="${fullData}">
                    More Details
                </button>
            </td>
        </tr>
    `;
});
tbody.innerHTML = rowsHtml;


});

// Using Bootstrap 5's modal JS
const detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
const modalDetailsBody = document.getElementById('modalDetailsBody');

document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('detailsBtn')) {
        const detailsData = JSON.parse(decodeURIComponent(e.target.getAttribute('data-details')));
        
        // Clear previous content
        modalDetailsBody.innerHTML = '';

        // Build table rows with all details (except those shown in main table to avoid duplication)
        for (const [key, value] of Object.entries(detailsData)) {
            // Skip keys shown in table columns if you want, or include all
            if (['call_id', 'ticket_number', 'category', 'Final_Status', 'created_at'].includes(key)) {
                continue; // skip or comment this if you want to show them too
            }
            modalDetailsBody.innerHTML += `
                <tr>
                    <th>${key.replace(/_/g, ' ')}</th>
                    <td>${value ?? ''}</td>
                </tr>
            `;
        }

        // Show the modal
        detailsModal.show();
    }
});

</script> -->
 <script>
document.getElementById("filterBtn").addEventListener("click", function() {
    let formData = new FormData(document.getElementById("filterForm"));

    fetch("{{ route('reports.voicecalls.search') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        let tbody = document.querySelector("#resultsTable tbody");
        tbody.innerHTML = "";
        if (data.length === 0) {
            tbody.innerHTML = "<tr><td colspan='8' class='text-center'>No Data Found</td></tr>";
            return;
        }
        let rowsHtml = '';
        data.forEach(row => {
            let fullData = encodeURIComponent(JSON.stringify(row));
            rowsHtml += `
                <tr>
                    <td>${row.call_id ?? ''}</td>
                    <td>${row.ticket_number ?? ''}</td>
                    <td>${row.category ?? ''}</td>
                    <td>${row.Final_Status ?? ''}</td>
                    <td>${row.created_at ?? ''}</td>
                    <td>
                        <button class="btn btn-sm btn-info detailsBtn" data-details="${fullData}">
                            More Details
                        </button>
                    </td>
                    <td>
                    <button class="btn btn-sm btn-warning studentDataBtn" data-student-id="${row.stud_id ?? ''}">
                          Student Data
                    </button>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = rowsHtml;
    });  // <-- This closes the .then(data => { ... }) callback
});  // <-- This closes the addEventListener callback

// Using Bootstrap 5's modal JS
const detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
const modalDetailsBody = document.getElementById('modalDetailsBody');

document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('detailsBtn')) {
        const detailsData = JSON.parse(decodeURIComponent(e.target.getAttribute('data-details')));
        
        // Clear previous content
        modalDetailsBody.innerHTML = '';

        // Build table rows with all details including those shown in the table
        for (const [key, value] of Object.entries(detailsData)) {
            let label = key.replace(/_/g, ' ');

            // Rename 'parent_name' to 'Name'
            if (key === 'parent_name') {
                label = 'Name';
            }

            modalDetailsBody.innerHTML += `
                <tr>
                    <th>${label}</th>
                    <td>${value ?? ''}</td>
                </tr>
            `;
        }

        // Show the modal
        detailsModal.show();
    }
});
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('studentDataBtn')) {
        const studentId = e.target.getAttribute('data-student-id');
        if (!studentId) {
           showAlert('Student ID not found for this record.', 'warning', 'mainAlert');

            return;
        }
        // Show the student modal and load data
        openStudentModal(studentId);
    }
});
function openStudentModal(studentId) {
    const modalEl = document.getElementById('StatusModal');
    const modal = new bootstrap.Modal(modalEl);

    // Reset modal content: hide content, show loader, hide alerts
    document.getElementById('statusLoader').style.display = 'block';
    document.getElementById('statusContent').style.display = 'none';
    document.getElementById('modalAlert').classList.add('d-none');
    document.getElementById('modalAlert').textContent = '';

    modal.show();

    // Fetch student data from your backend API
    fetch(`{{ url('/get-student') }}/${studentId}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (!data.success) {
                document.getElementById('statusLoader').style.display = 'none';
                document.getElementById('modalAlert').textContent = data.message || 'Failed to load student data.';
                document.getElementById('modalAlert').classList.remove('d-none');
                return;
            }

            // Hide loader, show content
            document.getElementById('statusLoader').style.display = 'none';
            document.getElementById('statusContent').style.display = 'block';

            // Fill modal fields with student data
            document.getElementById('studentName').textContent = data.student.name || 'N/A';
            document.getElementById('studentMajor').textContent = data.student.major || 'N/A';
            document.getElementById('studentBatch').textContent = data.student.batch || 'N/A';
            document.getElementById('studentSemester').textContent = data.student.semester || 'N/A';
            document.getElementById('studentStatus').textContent = data.student.status || 'N/A';
            document.getElementById('studentCgpa').textContent = data.student.last_cgpa || 'N/A';

            // Populate subjects table
    // Populate subjects table
// Populate subjects table
const subjectsTable = document.getElementById('studentSubjectsTable');
subjectsTable.innerHTML = '';

if (data.clearance && data.clearance.length > 0) {
    const seenSubjectCodes = new Set();

    data.clearance.forEach(row => {
        if (!seenSubjectCodes.has(row.course_code)) {  // Use the correct property for subject code
            seenSubjectCodes.add(row.course_code);

            const tr = `
                <tr>
                    <td>${row.semester || ''}</td>
                    <td>${row.course_code || ''}</td>  <!-- Subject code cell -->
                    <td>${row.course_name || ''}</td>
                    <td>${row.clearance_grade || ''}</td>
                    <td>${row.remark || ''}</td>
                </tr>`;
            subjectsTable.insertAdjacentHTML('beforeend', tr);
        }
    });
} else {
    subjectsTable.innerHTML = `<tr><td colspan="5" class="text-center">No data available</td></tr>`;
}
            // Populate tickets table
            const ticketsTable = document.getElementById('ticketsTable');
            ticketsTable.innerHTML = '';
            if (data.tickets && data.tickets.length > 0) {
                data.tickets.forEach(ticket => {
                    const row = `
                        <tr>
                            <td>${ticket.trackid || ''}</td>
                            <td>${ticket.subject || ''}</td>
                            <td><a href="https://hdesk.fu.edu.sd/admin/admin_ticket.php?track=${ticket.trackid}" target="_blank">View</a></td>
                            <td><a href="javascript:void(0);" data-bs-dismiss="modal" onclick="fillTicketForm('${ticket.trackid}')">Get</a></td>
                            <td>${ticket.priority || ''}</td>
                        </tr>`;
                    ticketsTable.insertAdjacentHTML('beforeend', row);
                });
            } else {
                ticketsTable.innerHTML = `<tr><td colspan="5" class="text-center">No tickets found</td></tr>`;
            }
        })
        .catch(error => {
            document.getElementById('statusLoader').style.display = 'none';
            const alertBox = document.getElementById('modalAlert');
            alertBox.textContent = 'Error loading student data: ' + error.message;
            alertBox.classList.remove('d-none');
        });
}
function showAlert(message, type = 'danger', targetId = 'mainAlert') {
    const alertBox = document.getElementById(targetId);

    if (alertBox) {
        alertBox.className = `alert alert-${type}`;
        alertBox.textContent = message;
        alertBox.classList.remove('d-none');

        // Only auto-hide if it is NOT the modal alert
        if (targetId !== 'modalAlert') {
            setTimeout(() => {
                alertBox.classList.add('d-none');
            }, 3000);
        } 
    }
}


</script>

  @endsection