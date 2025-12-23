<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>Student & Tickets</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
</head>
<body class="p-4">

    <!-- 🔎 إدخال البحث -->
    <div class="mb-3 d-flex gap-2">
        <input type="text" id="indexInput" class="form-control w-25" placeholder="أدخل رقم الطالب">
        <input type="text" id="ticketno" class="form-control w-25" placeholder="أدخل رقم التذكرة">
        <button id="btngetstdrecord" class="btn btn-primary">بحث</button>
    </div>

    <!-- 📌 بيانات الطالب -->
    <div class="card mb-4">
        <div class="card-header">بيانات الطالب</div>
        <div class="card-body row g-3">
            <div class="col-md-6">
                <label class="form-label">Student ID</label>
                <input type="text" id="stdindexno" class="form-control" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" id="name" class="form-control" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">Faculty</label>
                <input type="text" id="facultyInput" class="form-control" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">Major</label>
                <input type="text" id="majorInput" class="form-control" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">Batch</label>
                <input type="text" id="batchInput" class="form-control" readonly>
            </div>
        </div>
    </div>

    <!-- 🎟️ بيانات التذاكر -->
    <div class="card mb-4">
        <div class="card-header">التذاكر</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Subject</th>
                        <th>Link</th>
                        <th>Priority</th>
                    </tr>
                </thead>
                <tbody id="ticketsTable"></tbody>
            </table>
        </div>
    </div>

    <!-- 📑 التذكرة الحالية -->
    <div class="card mb-4">
        <div class="card-header">بيانات التذكرة الحالية</div>
        <div class="card-body row g-3">
            <div class="col-md-6">
                <label class="form-label">Ticket Number</label>
                <input type="text" id="ticketNumber" class="form-control" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Ticket URL</label>
                <input type="text" id="ticketURL" class="form-control" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <input type="text" id="foundStatus" class="form-control" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">Priority</label>
                <input type="text" id="priority" class="form-control" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">Assigned To</label>
                <input type="text" id="assignedTo" class="form-control" readonly>
            </div>
        </div>
    </div>

    <!-- 📌 Modal عرض التفاصيل -->
    <div class="modal fade" id="studentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تفاصيل الطالب</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><b>الاسم:</b> <span id="studentName"></span></p>
                    <p><b>التخصص:</b> <span id="studentMajor"></span></p>
                    <p><b>الدفعة:</b> <span id="studentBatch"></span></p>
                    <p><b>الفصل:</b> <span id="studentSemester"></span></p>
                    <p><b>الحالة:</b> <span id="studentStatus"></span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- 🟦 سكريبت -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('btngetstdrecord').addEventListener('click', function() {
        const studentId = document.getElementById('indexInput').value.trim();
        const ticketno  = document.getElementById('ticketno').value.trim();

        if (!studentId && ticketno) {
            getTicketRecords(ticketno);
        } else if (studentId) {
            getStudentRecord(studentId);
        } else {
            alert("Please enter Student ID or Ticket No");
        }
    });

    function getStudentRecord(studentId) {
        fetch(`{{ url('/get-student') }}/${studentId}`)
            .then(r => r.json())
            .then(data => {
                if (!data.success) return alert(data.message || 'Error loading student data');

                const student = data.student;
                const tickets = data.tickets || [];

                fillStudentForm(student);
                fillStudentModal(student);

                if (tickets.length > 0) {
                    fillTicketForm(tickets[0]);
                    fillTicketsTable(tickets);
                }
            })
            .catch(err => alert("Error: " + err.message));
    }

    function getTicketRecords(ticketno) {
        fetch(`/search-ticket/${ticketno}`)
            .then(r => r.json())
            .then(data => {
                if (!data.success) return alert(data.message || 'Error loading ticket data');

                fillStudentForm(data.student || {});
                fillStudentModal(data.student || {});
                fillTicketForm(data.ticket || {});
            })
            .catch(err => alert("Error: " + err.message));
    }

    function fillStudentForm(student) {
        document.getElementById('stdindexno').value  = student.stud_id || '';
        document.getElementById('name').value        = student.name || '';
        document.getElementById('facultyInput').value= student.faculty || '';
        document.getElementById('batchInput').value  = student.batch || '';
        document.getElementById('majorInput').value  = student.major || '';
    }

    function fillStudentModal(student) {
        document.getElementById('studentName').textContent    = student.name || 'N/A';
        document.getElementById('studentMajor').textContent   = student.major || 'N/A';
        document.getElementById('studentBatch').textContent   = student.batch || 'N/A';
        document.getElementById('studentSemester').textContent= student.semester || 'N/A';
        document.getElementById('studentStatus').textContent  = student.status || 'N/A';
    }

    function fillTicketForm(ticket) {
        document.getElementById('ticketNumber').value = ticket.trackid || '';
        document.getElementById('ticketURL').value    = `/ticket/${ticket.trackid || ''}`;
        document.getElementById('foundStatus').value  = ticket.status || '';
        document.getElementById('priority').value     = ticket.priority || '';
        document.getElementById('assignedTo').value   = ticket.openedby || '';
    }

    function fillTicketsTable(tickets) {
        const tbody = document.getElementById('ticketsTable');
        tbody.innerHTML = "";
        tickets.forEach(t => {
            const row = `
                <tr>
                    <td>${t.trackid || ''}</td>
                    <td>${t.subject || ''}</td>
                    <td><a href="/ticket/${t.trackid}" target="_blank">View</a></td>
                    <td>${t.priority || ''}</td>
                </tr>
            `;
            tbody.insertAdjacentHTML("beforeend", row);
        });
    }
    </script>
</body>
</html>
