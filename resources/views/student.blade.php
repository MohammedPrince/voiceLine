@extends('layouts.app')

@section('title', 'Call Page')

@section('content')



<form action="{{ route('voicecalls.store') }}" method="POST">
    @csrf
    <!-- Radios -->
    @if(session('success'))
    <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
    </div>
    @endif


    <div class="field caller-tabs"
        style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 10px; margin-bottom: 80px;">

        <div class="radio-group">
            <div class="slider"></div>

            <input type="radio" name="customer_type" id="caller-student" value="student" hidden checked>
            <label for="caller-student">
                <span>Student</span>
                <img src="assets/student.png" alt="student" class="tabicon">
            </label>

            <input type="radio" name="customer_type" id="caller-parent" value="parent" hidden>
            <label for="caller-parent">
                <span>Parent</span>
                <img src="assets/parent.png" alt="parent" class="tabicon">
            </label>

            <input type="radio" name="customer_type" id="caller-staff" value="staff" hidden>
            <label for="caller-staff">
                <span>Staff</span>
                <img src="assets/staff.png" alt="staff" class="tabicon">
            </label>

            <input type="radio" name="customer_type" id="caller-general" value="general" hidden>
            <label for="caller-general">
                <span>General</span>
                <img src="assets/general.png" alt="general" class="tabicon">
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
                <button type="button" data-bs-target="#StatusModal" data-bs-toggle="modal" id="btngetstdrecord"
                    class="view-btn">
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
                <option value="1">Data Follow and Verification</option>
                <option value="42">General Inquiries</option>
                <option value="3">Finance</option>
                <option value="2">Certificates and Statements</option>
                <option value="14">E-Learning</option>
                <option value="28">Update Ministry Graduates List</option>
                <option value="16">CESD / CTS (Staff only)</option>
                <option value="43">Human Resources</option>
                <option value="24">Reports</option>
                <option value="23">Higher Management</option>
                <option value="30">External Transfer & Elevation</option>
                <option value="31">New Admission</option>
                <option value="32">Faculty of Geoinformatics</option>
                <option value="33">Fine Arts & Interior Design</option>
                <option value="34">Faculty of Architecture</option>
                <option value="35">Telecommunication & Space Tech</option>
                <option value="37">Information Technology</option>
                <option value="38">Engineering</option>
                <option value="39">Computer Sciences</option>
                <option value="40">Business Administration</option>
                <option value="41">Postgraduate Studies</option>
                <option value="44">BetterU Service</option>
                <option value="45">Technology Horizon Journal</option>
                <!-- Other options -->
            </select>
        </div>
        <div id="ticket-fields-group">
            <div class="flex">
                <div class="field">
                    <div class="label">Ticket Number</div>
                    <input type="text" id="ticketNumber" name="ticket_number" class="form-control" readonly>
                </div>

                <div class="field">
                    <div class="label">Ticket URL</div>
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
                                    <th style="color: #EC8305;">Remark</th>
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

<!-- Modal -->
<div class="modal fade" id="StatusModal" tabindex="-1" aria-labelledby="StatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="StatusModalLabel" style="color: #EC8305;">Student Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <p><b>Name:</b> <span id="studentName"> {{ session('name') }} </span></p>
                <p><b>Major:</b> <span id="studentMajor"> {{ session('name') }}</span></p>
                <p><b>Batch:</b> <span id="studentBatch"> {{ session('batch') }}</span></p>
                <p><b>Semester:</b> <span id="studentSemester"> {{ session('semester') }}</span></p>
                <p><b>Status:</b> <span id="studentStatus"> {{ session('status') }}</span></p>

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
                                    <th style="color: #EC8305;">Course</th>
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

                @endsection
                @push('scripts')
                <!-- Bootstrap JS -->

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
                <!-- Toggle Logic in JS -->
                <script>
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
                        if ((!studentId || studentId.trim() === "") && ticketno && ticketno.length > 0) {
                            // Case 1: studentId is null/empty AND ticketId exists
                            console.log('ticketno');
                            get_ticket_records(ticketno);
                        } else if (studentId && studentId.trim() !== "") {
                            // Case 2: studentId has value

                            getStudentRecord(studentId);
                        } else {
                            // Case 3: neither provided
                            alert("Please enter Std Id or TicketID");
                        }

                    }
                );

                function getStudentRecord(studentId) {
                    console.log("Fetching student data:", `/get-student/${studentId}`);

                    fetch(`{{ url('/get-student') }}/${studentId}`)
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        })
                        .then(data => {
                            if (!data.success) {
                                alert(data.message || 'Error loading student data');
                                return;
                            }

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
                            const subjectsTable = document.getElementById('studentSubjectsTable');
                            subjectsTable.innerHTML = "";

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
                                subjectsTable.innerHTML =
                                    `<tr><td colspan="4" class="text-center">No data available</td></tr>`;
                            }


                        })

                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error loading student data: ' + error.message);
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
                                alert("لم يتم العثور على بيانات التذكرة");
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
                            alert("حصل خطأ أثناء تحميل بيانات التذكرة: " + error.message);
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