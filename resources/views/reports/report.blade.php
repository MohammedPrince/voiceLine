<!-- resources/views/reports/voicecalls.blade.php -->
 

 
<div class="container">
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

        <div class="col-12">
            <button type="button" id="filterBtn" class="btn btn-primary">Search</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
    </form>

    <!-- جدول النتائج -->
    <table class="table table-bordered" id="resultsTable">
        <thead>
            <tr>
                <th>Call ID</th>
                <th>Ticket</th>
                <th>Customer Type</th>
                <th>Parent Name</th>
                <th>Category</th>
                <th>Issue</th>
                <th>Final Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

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
        data.forEach(row => {
            tbody.innerHTML += `
                <tr>
                    <td>${row.call_id ?? ''}</td>
                    <td>${row.ticket_number ?? ''}</td>
                    <td>${row.customer_type ?? ''}</td>
                    <td>${row.parent_name ?? ''}</td>
                    <td>${row.category ?? ''}</td>
                    <td>${row.issue ?? ''}</td>
                    <td>${row.Final_Status ?? ''}</td>
                    <td>${row.created_at ?? ''}</td>
                </tr>
            `;
        });
    });
});
</script>
 
