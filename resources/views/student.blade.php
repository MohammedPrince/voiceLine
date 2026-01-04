@extends('layouts.app')

@section('title', 'Call Page')

@section('content')

<div id="mainAlert" class="alert alert-danger d-none" role="alert"></div>

<form action="{{ route('voicecalls.store') }}" method="POST">
    @csrf
    <!-- Radios -->
    @if(session('success'))
    <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
    </div>
    @endif
@if(session('exact_error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-left: 5px solid #842029;">
        <div class="d-flex">
            <div class="py-1">
                <svg class="h-6 w-6 text-danger me-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" style="width:24px; fill:currentColor;">
                    <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm1.41-1.41A8 8 0 1 0 15.66 4.34 8 8 0 0 0 4.34 15.66zm9.54-7.37A1 1 0 1 1 12.47 9.7l-2.47-2.48-2.47 2.48A1 1 0 1 1 6.12 8.29l2.47-2.48-2.47-2.47a1 1 0 1 1 1.41-1.42l2.47 2.47 2.47-2.47a1 1 0 0 1 1.41 1.42L11.06 7.22l2.47 2.48z"/>
                </svg>
            </div>
            <div class="w-100">
                <p class="fw-bold mb-1">Submission Failed</p>
                <p class="mb-2">Please make sure all required fields are filled correctly.</p>
                
                <button id="toggleErrorBtn" 
                        class="btn btn-sm btn-outline-danger" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#errorDetails"
                        onclick="this.remove()">
                    Show More Details
                </button>

                <div class="collapse mt-3" id="errorDetails">
                    <div class="card card-body bg-light border-0 p-2">
                        <small class="text-muted mb-1 fw-bold">Technical Error:</small>
                        <code id="errorMsg" style="color: #842029; font-size: 0.75rem; word-break: break-all;">
                            {{ session('exact_error') }}
                        </code>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
    <div class="field caller-tabs"
        style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 10px; margin-bottom: 80px;">

        <div class="radio-group">
            <div class="slider"></div>

            <input type="radio" name="customer_type" id="caller-student" value="student" hidden checked>
            <label for="caller-student">
                <span>Student</span>
                
                <img src="{{ asset('assets/student.png') }}" alt="student" class="tabicon">
            </label>

            <input type="radio" name="customer_type" id="caller-parent" value="parent" hidden>
            <label for="caller-parent">
                <span>Parent</span>
                <img src="{{ asset('assets/parent.png') }}" alt="parent" class="tabicon">
            </label>

            <input type="radio" name="customer_type" id="caller-staff" value="staff" hidden>
            <label for="caller-staff">
                <span>Staff</span>
                <img src="{{ asset('assets/staff.png') }}" alt="staff" class="tabicon">
            </label>

            <input type="radio" name="customer_type" id="caller-general" value="general" hidden>
            <label for="caller-general">
                <span>General</span>
                <img src="{{ asset('assets/general.png') }}" alt="general" class="tabicon">
            </label>
        </div>
    </div>
    <div class="call-form" style="margin-top:-10vh;">
        <!-- Always visible -->
        <div class="field" id="index-field" style="display: flex; flex-direction: row; gap: 20px;">
            <div style="flex: 1 1 25%;">
                <div class="label">Index</div>
                <input type="text" id="indexInput" name="stud_index" style="width: 100%;">
                <div style="flex: 1 1 25%; display:none;">
                    <div class="label">Get Ticket #</div>
                    <input type="text" id="ticketno" style="width: 100%;">
                </div>

                <div style="flex: 1 1 25%; margin-top:20px; display:none;">
                    <div class="label">Student Index #:</div>
                    <div class="label" id="stud_id" name="stud_id"></div>
                    <input type="hidden" name="stdindexno" id="stdindexno">
                </div>
            </div>
            <div style="flex: 1 1 25%;">
                <button type="button" id="btngetstdrecord"  class="view-btn">
                    View Status
                </button>



            </div>
            <div style="flex: 1 1 35%; display:none;">
                <button type="button" data-bs-target="#StatusModal" data-bs-toggle=" " id="AcademicRecord"
                    onclick="submitStudentForm()" class="view-btn">
                    Get Academic Rec.
                </button>

            </div>
        </div>

        <div class="field" id="staff-id-field" style="display: flex; flex-direction: row; gap: 10px;">
            <div class="flex">
                <div class="field" style="flex: 1 1 30%;">
                    <div class="label">Staff ID</div>
                    <input type="text" id="id" name="staff_id">
                </div>

                <div class="field" style="flex: 1 1 30%;">
                    <button type="button" data-bs-target="#StaffModal" data-bs-toggle="modal" id="" class="view-btn"
                        style="width:30%;">
                        Get Info
                    </button>

                </div>

            </div>

        </div>

        <div class="flex">
            <div class="field" id="name-field">
                <div class="label">Name</div>
                <input type="text" id="name" name="caller_Name">
            </div>
            <!-- <div class="field" id="id-field">
        <div class="label">Staff ID</div>
        <input type="text" id="id" name="staff_id">
      </div> -->
            <div class="field" id="parent-field">
                <div class="label">Phone</div>
                <input type="text" id="phone" name="phone">
            </div>
        </div>

        <!-- Conditional fields -->
        <div class="flex">
            <div class="field" id="faculty-field">
                <div class="label">Faculty</div>
                <input type="text" id="facultyInput" class="form-control" readonly>
            </div>

            <div class="field" id="batch-field">
                <div class="label">Batch</div>
                <input type="text" id="batchInput" class="form-control" readonly>
            </div>

            <div class="field" id="major-field">
                <div class="label">Major</div>
                <input type="text" id="majorInput" class="form-control" readonly>
            </div>
        </div>

        <!-- Rest of your form -->
        <div class="field">
            <div class="label">Issue</div>
            <textarea id="issue" name="issue" rows="4"></textarea>
        </div>
        <div class="field">
            <div class="label">Category</div>
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
        <div id="ticket-fields-group">
            <div class="flex">
                <div class="field">
                    <div class="label">Ticket Number</div>
                    <input type="text" id="ticketNumber" name="ticket_number" class="form-control" readonly>
                </div>

                <div class="field">
                    <div class="label">Ticket Subject</div>
                    <input type="text" id="ticketURL" name="ticket_url" class="form-control" readonly>
                </div>

                <div class="field">
                    <div class="label">Found Status</div>
                    <input type="text" id="foundStatus" name="foundStatus" class="form-control" readonly>
                </div>
            </div>

            <div class="flex">
                <div class="field">
                    <div class="label">Priority</div>
                    <input type="text" id="priority" name="priority" class="form-control" readonly>
                </div>

                <div class="field">
                    <div class="label">Assigned To</div>
                    <input type="text" id="assignedTo" name="assignedTo" class="form-control" readonly>
                </div>
            </div>
        </div>
        <div class="field">
            <div class="label">Final Status</div>
            <select id="finalStatus" name="Final_Status">
                <option value="" selected></option>
                <option value="1">Resolved</option>
                <option value="2">Submitted</option>
                <option value="3">Escalated</option>
            </select>
        </div>

        <div class="field">
            <div class="label">Solution Note</div>
            <textarea id="solutionNote" name="Solution_Note" rows="5"></textarea>
        </div>

        <div class="btn">
            <button type="submit" class="submit-btn">Submit</button>
        </div>
    </div>
</form>
<!---------------------------------------------------staff Modal -->
<div class="modal fade" id="StaffModal" tabindex="-1" aria-labelledby="StaffModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="StaffModalLabel" style="color: #EC8305;">Staff Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <p><b>Staff Name:</b> <span id="staffName">N/A</span></p>
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
                                    <th style="color: #EC8305;">Priorety</th>
                                </tr>
                            </thead>
                            <tbody id="staffTicketsTable">
                                <tr>
                                    <td colspan="5" class="text-center">No data available</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!----------------------------------------- student Modal --------->
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


           
                @endsection
                @push('scripts')
                <!-- Bootstrap JS -->

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
                <!-- Toggle Logic in JS -->
                <script>


// custom alert dialog


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

// view status error handling code 
document.getElementById('btngetstdrecord').addEventListener('click', function() {
    const studentId = document.getElementById('indexInput').value;
    const ticketno = document.getElementById('ticketno').value;

    // Check for empty inputs first
    if ((!studentId || studentId.trim() === "") && (!ticketno || ticketno.trim() === "")) {
        // Case: Everything is empty - DON'T show modal
        showAlert("Please enter Student ID", "warning" , "mainAlert");
        return; // Stop here
    }

    // If we have data, manually show the modal and start the fetch
    const modalEl = document.getElementById('StatusModal');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    // Now handle the logic as before
    if ((!studentId || studentId.trim() === "") && ticketno) {
        get_ticket_records(ticketno);
    } else {
        getStudentRecord(studentId);
    }
});




                const radios = document.querySelectorAll('input[name="customer_type"]');
                const fields = {
                    parent: document.getElementById("parent-field"),
                    faculty: document.getElementById("faculty-field"),
                    batch: document.getElementById("batch-field"),
                    major: document.getElementById("major-field"),
                    index: document.getElementById("index-field"),
                    // id: document.getElementById("id-field"),
                    ticketGroup: document.getElementById("ticket-fields-group"),
                    staffId: document.getElementById("staff-id-field"),
                };

                // const labels = document.querySelectorAll(".radio-group label");
                const slider = document.querySelector(".slider");

                // Position slider behind the selected label
                function moveSliderTo(label) {
                    const rect = label.getBoundingClientRect();
                    const parentRect = label.parentElement.getBoundingClientRect();

                    slider.style.width = rect.width + "px";
                    slider.style.height = rect.height + "px";
                    slider.style.transform = `translateX(${rect.left - parentRect.left}px)`;
                }

                // Show/hide fields based on selected role
                function updateVisibility(role) {
                    // Hide all fields first
                    Object.values(fields).forEach(f => f.style.display = "none");

                    if (role === "student") {
                        fields.faculty.style.display = "block";
                        fields.batch.style.display = "block";
                        fields.major.style.display = "block";
                        fields.index.style.display = "flex";
                        fields.ticketGroup.style.display = "block";
                    } else if (role === "parent") {
                        fields.parent.style.display = "block";
                        fields.faculty.style.display = "block";
                        fields.batch.style.display = "block";
                        fields.major.style.display = "block";
                        fields.index.style.display = "flex";
                        fields.ticketGroup.style.display = "block";
                    } else if (role === "staff") {
                        fields.staffId.style.display = "block";
                        fields.faculty.style.display = "block";
                        // fields.id.style.display = "block";
                        fields.ticketGroup.style.display = "block";
                    } else if (role === "general") {
                        fields.parent.style.display = "block";

                    }

                    // Update active label + move slider
                    document.querySelectorAll(".radio-group label").forEach(lbl => lbl.classList.remove("active"));
                    const activeLabel = document.querySelector(`label[for="caller-${role}"]`);
                    activeLabel.classList.add("active");
                    moveSliderTo(activeLabel);
                }

                // Event listeners
                radios.forEach(r => r.addEventListener("change", e => updateVisibility(e.target.value)));

                // Initial position
                window.addEventListener("load", () => {
                    const checked = document.querySelector('input[name="customer_type"]:checked');
                    if (checked) {
                        updateVisibility(checked.value);
                    }
                });

                // Recalculate on resize
                window.addEventListener("resize", () => {
                    const checked = document.querySelector('input[name="customer_type"]:checked');
                    if (checked) {
                        updateVisibility(checked.value);
                    }
                });

                // Get student record function
                document.getElementById('btngetstdrecord').addEventListener('click', function() {
                        const studentId = document.getElementById('indexInput').value;
                        const ticketno = document.getElementById('ticketno').value;

                        console.log(studentId);
                        console.log(ticketno);
           
                        document.getElementById('modalAlert').classList.add('d-none');
                        if ((!studentId || studentId.trim() === "") && ticketno && ticketno.length > 0) {
                            // Case 1: studentId is null/empty AND ticketId exists
                            console.log('ticketno');
                            get_ticket_records(ticketno);
                        } else if (studentId && studentId.trim() !== "") {
                            // Case 2: studentId has value

                            getStudentRecord(studentId);
                        } else {
                            // // Case 3: neither provided
                            // showAlert("Please enter Std Id or TicketID","warning");
                        }

                    }
                );

                function getStudentRecord(studentId) {
                       // SHOW loader
                       document.getElementById('statusLoader').style.display = 'block';
                        document.getElementById('statusContent').style.display = 'none';
                    console.log("Fetching student data:", `/get-student/${studentId}`);

                    fetch(`{{ url('/get-student') }}/${studentId}`)
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        })
                        .then(data => {
                            if (!data.success) {

                                showAlert(data.message || 'Error loading student data','modalAlert');
                                return;
                            }

                           //  HIDE loader, SHOW content
                            document.getElementById('statusLoader').style.display = 'none';
                            document.getElementById('statusContent').style.display = 'block';
                            // ✅ Fill the form fields
                            document.getElementById('stud_id').innerText = data.student.stud_id || '';
                            document.getElementById('stdindexno').value = data.student.stud_id || '';
                            document.getElementById('name').value = data.student.name || '';
                            document.getElementById('facultyInput').value = data.student.faculty || '';
                            document.getElementById('batchInput').value = data.student.batch || '';
                            document.getElementById('majorInput').value = data.student.major || '';

                            // ✅ Fill the modal content
                            document.getElementById('studentName').textContent = data.student.name || 'N/A';
                            document.getElementById('studentMajor').textContent = data.student.major || 'N/A';
                            document.getElementById('studentBatch').textContent = data.student.batch || 'N/A';
                            document.getElementById('studentSemester').textContent = data.student.semester || 'N/A';
                            document.getElementById('studentStatus').textContent = data.student.status || 'N/A';
                            document.getElementById('studentCgpa').textContent = data.student.last_cgpa || 'N/A';
 console.log('Tickets data:', data.tickets);
                            // ✅ Clear old tickets
                            const ticketsTable = document.getElementById('ticketsTable');
                            ticketsTable.innerHTML = "";

                            if (data.tickets && data.tickets.length > 0) {
                                data.tickets.forEach(ticket => {
                                    // added data-bs-dismiss="modal" to get button to close the modal and the backdrop
                                    const row = `
                    
                        <tr>
                            <td>${ticket.trackid || ''}</td>
                            <td>${ticket.subject || ''}</td>
                            <td><a href="https://hdesk.fu.edu.sd/admin/admin_ticket.php?track=${ticket.trackid}" target="_blank" >View</a></td>
                            
                           <td><a href="javascript:void(0);" data-bs-dismiss="modal" onclick="fillTicketForm('${ticket.trackid}')">Get</a></td>
                            <td>${ticket.priority || ''}</td>
                        </tr>`;
                                    ticketsTable.insertAdjacentHTML('beforeend', row);
                                });
                            } else {
                                ticketsTable.innerHTML =
                                    `<tr><td colspan="4" class="text-center">No tickets found</td></tr>`;
                            }
                            // ✅ تفريغ الجدول قبل التحديث
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



                        })

                        .catch(error => {
                             document.getElementById('statusLoader').style.display = 'none';
                            console.error('Error:', error);
                           
                            showAlert('Error loading student data: ' + error.message, 'danger', 'modalAlert');
                        });
                }

                function fillTicketForm(trackid) {
                    fetch(`/search-ticket/${trackid}`)
                        .then(response => {
                            if (!response.ok) { // لو السيرفر رجّع 404 أو 500
                                throw new Error('HTTP error! status: ' + response.status);
                            }
                            return response.json();
                        })
                        .then(data => {
                         

                            if (!data.success) {
                                showAlert("لم يتم العثور على بيانات التذكرة");

                                return;
                            }
  
                            const ticket = data.ticket;

                            document.getElementById('ticketNumber').value = ticket.trackid || '';
                            document.getElementById('assignedTo').value = ticket.owner_name || '';
                            document.getElementById('priority').value = ticket.priority || '';
                            document.getElementById('ticketURL').value = ticket.subject || '';
                            document.getElementById('foundStatus').value = ticket.foundStatus || '';



                            let modalEl = document.getElementById('StatusModal');
                            let modal = bootstrap.Modal.getInstance(modalEl); // get the one that was opened

                            modal.hide();

                        })
                        .catch(error => {
                            console.error("Error fetching ticket data:", error);
                            showAlert("حصل خطأ أثناء تحميل بيانات التذكرة: " + error.message);
                        });

                }
                </script>



                <form id="studentForm" action="{{ route('studentview') }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="name" id="hiddenName">
                    <input type="hidden" name="faculty" id="hiddenFaculty">
                    <input type="hidden" name="batch" id="hiddenBatch">
                    <input type="hidden" name="major" id="hiddenMajor">
                </form>
                <script>
                function submitStudentForm() {
                    // Copy values from visible fields to hidden form inputs
                    document.getElementById('hiddenName').value = document.getElementById('name').value;
                    document.getElementById('hiddenFaculty').value = document.getElementById('facultyInput').value;
                    document.getElementById('hiddenBatch').value = document.getElementById('batchInput').value;
                    document.getElementById('hiddenMajor').value = document.getElementById('majorInput').value;

                    // Submit the hidden form
                    document.getElementById('studentForm').submit();
                }
                </script>

                <script>
                // اختفائها رسالة التنبيه بحفظ السجل
                setTimeout(() => {
                    let msg = document.getElementById('success-message');
                    if (msg) {
                        msg.style.transition = "opacity 0.5s ease";
                        msg.style.opacity = "0";
                        setTimeout(() => msg.remove(), 500); // يحذف الرسالة بعد اختفائها
                    }
                }, 3000);
                </script>
                @endpush