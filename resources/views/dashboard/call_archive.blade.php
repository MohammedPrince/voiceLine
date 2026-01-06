@extends('layouts.app')

@section('title', 'Call Archive')

@section('content')
<style>
.table td, .table th {
    padding: 4px 8px !important;
    vertical-align: middle;
    white-space: nowrap;
}

#filterForm .form-label {
    font-weight: 600;
}

#filterForm .form-control, 
#filterForm select {
    font-size: 0.9rem;
    padding: 4px 6px;
}

.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 15px;
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

.pagination {
    margin: 0;
}

.page-info {
    color: #6c757d;
    font-size: 0.9rem;
}
</style>

<div class="reports-container mt-4">
    <h3 class="mb-3">Call Archive</h3>

    <!-- Filter Form -->
    <form id="filterForm" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label" for="start_date">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label" for="end_date">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label" for="ticket_number">Ticket Number</label>
            <input type="text" name="ticket_number" id="ticket_number" class="form-control" placeholder="Enter ticket number">
        </div>
        <div class="col-md-3">
    <label class="form-label" for="customer_type">Customer Type</label>
    <select name="customer_type" id="customer_type" class="form-select">
        <option value="" selected>Select Customer Type</option>
        <option value="Student">Student</option>
        <option value="Parent">Parent</option>
        <option value="Staff">Staff</option>
        <option value="General">General</option>
    </select>
</div>

        <div class="col-md-3">
            <label class="form-label" for="parent_name">Name</label>
            <input type="text" name="parent_name" id="parent_name" class="form-control" placeholder="Enter name">
        </div>
        <div class="col-md-3">
            <label class="form-label" for="stud_id">Student ID</label>
            <input type="text" name="stud_id" id="stud_id" class="form-control" placeholder="Enter student ID">
        </div>
        <div class="col-md-3">
            <label class="form-label" for="staff_ID">Staff ID</label>
            <input type="text" name="staff_ID" id="staff_ID" class="form-control" placeholder="Enter staff ID">
        </div>
        <div class="col-md-3">
            <label class="form-label" for="category">Category</label>
            <select id="category" name="category" class="form-select">
                <option value="" selected>All Categories</option>
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
            <button type="button" id="filterBtn" class="btn btn-primary btn-sm" style="max-width:120px;">
                Search
            </button>
            <button type="reset" id="resetBtn" class="btn btn-secondary btn-sm" style="max-width:120px;">
                Reset
            </button>
        </div>
    </form>

    <!-- Results Table -->
    <table class="table table-bordered" id="resultsTable">
        <thead>
                <tr>
                <th>Ticket Number</th>
                <th>student index</th>
                <th>Category</th>
                <th>Final Status</th>
                <th>More Info</th>
                <th>Update</th>
            </tr>
        </thead>
        <tbody id="callTableBody">
            <tr>
        <td colspan="10" class="text-center text-muted py-3">
            Press Search to display All calls.
        </td>
    </tr>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        <div class="page-info">
            Showing <span id="showingStart">0</span> to <span id="showingEnd">0</span> of <span id="totalRecords">0</span> records
        </div>
        <nav>
            <ul class="pagination mb-0" id="paginationControls">
                <!-- Pagination buttons will be inserted here -->
            </ul>
        </nav>
        <div class="d-flex align-items-center gap-2">
            <label for="perPageSelect" class="mb-0 me-2">Rows per page:</label>
            <select id="perPageSelect" class="form-select form-select-sm" style="width: auto;">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>
</div>


<!-- More Info Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Call Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered">
                    <tbody id="modalDetailsBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="updateForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Final Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="updateTicketNumber" name="ticket_number" value="">

                <div class="mb-3">
                    <label for="finalStatus" class="form-label">Final Status</label>
                    <select id="finalStatus" name="final_status" class="form-select" required>
                        <option value="">Select status</option>
                        <option value="Resolved">Resolved</option>
                        <option value="Submitted">Submitted</option>
                        <option value="Escalated">Escalated</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="statusNote" class="form-label">Update Note</label>
                    <textarea id="statusNote" name="status_note" class="form-control" rows="3" placeholder="Enter update note"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Category map based on your select options
const categoryMap = {
    1: "[Certificates] Graduates Lists",
    2: "[Certificates] Delayed Issuance",
    3: "[Certificates] Payment Issues",
    4: "[Certificates] General",
    5: "[Finance] Delayed Approval",
    6: "[Academic] Delayed Approval",
    7: "[Academic] Delayed Result",
    8: "[Academic] Registration",
    9: "[Academic] Verification",
    10: "[Academic] F/z result",
    11: "[Academic] Postgraduate",
    12: "[Academic] remarking",
    13: "[E-Learning] Account Activation",
    14: "[E-Learning] F/Z Course Enrolment",
    15: "[E-Learning] Wrong Courses",
    16: "[HelpDesk] Password Reset",
    17: "[General Inquiry] New Admission",
    18: "[General Inquiry] General",
    19: "[General inquiry] others",
    20: "[General inquiry] Complaint",
    21: "[Addmission] Internal/external transfer/تجسير",
    22: "[CTS] SMOWL issues",
    23: "[CTS] Exam Access",
    24: "[CTS] Close ticket /OPEN",
    25: "[CESD] Index issuing"
};

// Final Status map - handles both 1 and 4 as Resolved
const finalStatusMap = {
    '1': "Resolved",
    '4': "Resolved",  // Updated resolved status
    '2': "Submitted",
    '3': "Escalated"
};

$(document).ready(function() {
    const userId = @json(auth()->id());
    let allCalls = [];
    let filteredCalls = [];
    let currentPage = 1;
    let recordsPerPage = 10;

    // Initialize modals
    const detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
    const updateModal = new bootstrap.Modal(document.getElementById('updateModal'));

    // Get badge color based on status
    function getBadgeColor(status) {
        if (status === 'Resolved' || status === '1' || status === '4') return 'success';
        if (status === 'Submitted' || status === '2') return 'warning';
        if (status === 'Escalated' || status === '3') return 'danger';
        return 'secondary';
    }

    // Update table with pagination
    function updateTable(calls) {
        const tbody = $('#callTableBody');
        tbody.empty();

        if (!calls || calls.length === 0) {
            tbody.append('<tr><td colspan="10" class="text-center text-muted py-3">No calls found</td></tr>');
            updatePaginationInfo(0, 0, 0);
            renderPagination(0);
            return;
        }

        // Calculate pagination
        const totalRecords = calls.length;
        const totalPages = Math.ceil(totalRecords / recordsPerPage);
        const startIndex = (currentPage - 1) * recordsPerPage;
        const endIndex = Math.min(startIndex + recordsPerPage, totalRecords);
        
        // Get current page records
        const pageRecords = calls.slice(startIndex, endIndex);

        // Render table rows
        pageRecords.forEach(call => {
            const fullData = encodeURIComponent(JSON.stringify(call));
            
            // Convert numeric category to string if needed
            const categoryText = categoryMap[call.category] || call.category || 'N/A';

            // Convert numeric final status to string if needed
            const finalStatus = finalStatusMap[call.Final_Status] || call.Final_Status || 'N/A';

            tbody.append(`
                <tr>
                    <td>${call.ticket_number ?? 'N/A'}</td>
                    <td>${call.stud_id ?? 'N/A'}</td>
                    <td>${categoryText}</td>
                    <td>
                        <span class="badge bg-${getBadgeColor(finalStatus)}">
                            ${finalStatus}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-info detailsBtn" data-details="${fullData}">More Info</button>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning updateBtn" 
                            data-ticket="${call.ticket_number}"
                            data-status="${finalStatus}">
                            Update
                        </button>
                    </td>
                </tr>
            `);
        });

        // Update pagination
        updatePaginationInfo(startIndex + 1, endIndex, totalRecords);
        renderPagination(totalPages);
    }

    // Update pagination info text
    function updatePaginationInfo(start, end, total) {
        $('#showingStart').text(start);
        $('#showingEnd').text(end);
        $('#totalRecords').text(total);
    }

    // Render pagination controls
    function renderPagination(totalPages) {
        const paginationControls = $('#paginationControls');
        paginationControls.empty();

        if (totalPages <= 1) {
            return;
        }

        // Previous button
        paginationControls.append(`
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
            </li>
        `);

        // Page numbers
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, currentPage + 2);

        // First page
        if (startPage > 1) {
            paginationControls.append(`
                <li class="page-item">
                    <a class="page-link" href="#" data-page="1">1</a>
                </li>
            `);
            if (startPage > 2) {
                paginationControls.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
            }
        }

        // Page numbers
        for (let i = startPage; i <= endPage; i++) {
            paginationControls.append(`
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `);
        }

        // Last page
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                paginationControls.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
            }
            paginationControls.append(`
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a>
                </li>
            `);
        }

        // Next button
        paginationControls.append(`
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
            </li>
        `);

        // Attach click handlers
        paginationControls.find('a.page-link').on('click', function(e) {
            e.preventDefault();
            const page = parseInt($(this).data('page'));
            if (page && page !== currentPage && page >= 1 && page <= totalPages) {
                currentPage = page;
                updateTable(filteredCalls);
            }
        });
    }

    // Filter button
    $('#filterBtn').on('click', function() {
        const formData = {
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            ticket_number: $('#ticket_number').val().trim(),
            customer_type: $('#customer_type').val().trim(),
            parent_name: $('#parent_name').val().trim(),
            stud_id: $('#stud_id').val().trim(),
            staff_ID: $('#staff_ID').val().trim(),
            category: $('#category').val()
        };

        Object.keys(formData).forEach(key => {
            if (!formData[key]) delete formData[key];
        });

        $.ajax({
            url: '{{ route("usercalls") }}',
            method: 'GET',
            data: formData,
            success: function(data) {
                filteredCalls = data;
                currentPage = 1;
                updateTable(filteredCalls);
            },
            error: function(xhr) {
                console.error('Filter error:', xhr);
                alert('Failed to filter calls. Please try again.');
            }
        });
    });

    // Reset button
    $('#resetBtn').on('click', function() {
        $('#filterForm')[0].reset();
        filteredCalls = [];
        currentPage = 1;
        updateTable(filteredCalls);
    });

    // Records per page change
    $('#perPageSelect').on('change', function() {
        recordsPerPage = parseInt($(this).val());
        currentPage = 1;
        updateTable(filteredCalls);
    });

    // Details button click handler
    $(document).on('click', '.detailsBtn', function() {
        const detailsData = JSON.parse(decodeURIComponent($(this).data('details')));
        
        const modalDetailsBody = $('#modalDetailsBody');
        modalDetailsBody.empty();

        // Build table rows with all details
        for (const [key, value] of Object.entries(detailsData)) {
            let label = key.replace(/_/g, ' ');
            
            if (key === 'parent_name') {
                label = 'Name';
            }

            modalDetailsBody.append(`
                <tr>
                    <th style="width: 30%;">${label}</th>
                    <td>${value ?? 'N/A'}</td>
                </tr>
            `);
        }

        detailsModal.show();
    });

    // Update button click handler
    $(document).on('click', '.updateBtn', function() {
        const ticket = $(this).data('ticket');
        const status = $(this).data('status');

        $('#updateTicketNumber').val(ticket);
        $('#finalStatus').val(status);
        $('#statusNote').val('');

        updateModal.show();
    });

    // Submit update form with AJAX
    $('#updateForm').on('submit', function(e) {
        e.preventDefault();

        const formData = {
            ticket_number: $('#updateTicketNumber').val(),
            final_status: $('#finalStatus').val(),
            status_note: $('#statusNote').val(),
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: '{{ route("calls.update-status") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                alert(response.message);
                updateModal.hide();

                // Refresh the table to show updated status
                $('#filterBtn').click();
            },
            error: function(xhr) {
                let errMsg = 'Failed to update status.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errMsg = xhr.responseJSON.error;
                }
                alert(errMsg);
            }
        });
    });
});
</script>
@endpush