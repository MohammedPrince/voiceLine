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
            <label class="form-label">Parent Name</label>
            <input type="text" name="parent_name" class="form-control" placeholder="Enter parent name">
        </div>

        <div class="col-md-3">
            <label class="form-label">Staff ID</label>
            <input type="text" name="staff_ID" class="form-control" placeholder="Enter staff ID">
        </div>

        <div class="col-md-3">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" placeholder="Enter category">
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
                    <button class="btn btn-sm btn-warning studentDataBtn" data-student-id="${row.student_id ?? ''}">
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
            const subjectsTable = document.getElementById('studentSubjectsTable');
            subjectsTable.innerHTML = '';
            if (data.clearance && data.clearance.length > 0) {
                data.clearance.forEach(row => {
                    const tr = `
                        <tr>
                            <td>${row.semester || ''}</td>
                            <td>${row.course_name || ''}</td>
                            <td>${row.clearance_grade || ''}</td>
                            <td>${row.remark || ''}</td>
                        </tr>`;
                    subjectsTable.insertAdjacentHTML('beforeend', tr);
                });
            } else {
                subjectsTable.innerHTML = `<tr><td colspan="4" class="text-center">No data available</td></tr>`;
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