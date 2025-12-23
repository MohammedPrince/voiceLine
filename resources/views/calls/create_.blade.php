@extends('layouts.app')

@section('title', 'تسجيل مكالمة')

@push('styles')
<style>
    body { background-color: #f8f9fa; }
    .dashboard-wrapper { display: flex; min-height: 80vh; }
    .sidebar {
        width: 220px; background: #343a40; color: #fff;
        padding: 20px; border-radius: 8px; margin-right: 15px;
    }
    .sidebar h5 { color: #adb5bd; font-size: 14px; margin-bottom: 15px; }
    .sidebar .caller-type-btn {
        display: block; width: 100%; text-align: left; margin-bottom: 10px;
        padding: 12px; border-radius: 8px; font-size: 15px; 
        background: transparent; color: #fff; border: 1px solid #495057;
        transition: 0.3s;
    }
    .sidebar .caller-type-btn:hover,
    .sidebar .caller-type-btn.active {
        background: #0d6efd; border-color: #0d6efd; color: #fff;
    }

    .main-content {
        flex: 1; background: #fff; padding: 20px; border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .section-title { font-size: 16px; font-weight: 600; margin-bottom: 10px; color: #0d6efd; }
    .student-info, .ticket-info {
        background: #f8f9fa; border-left: 4px solid #0d6efd; padding: 10px;
        margin-top: 10px; border-radius: 6px;
    }
    .info-label { font-weight: bold; color: #495057; }
</style>
@endpush

@section('content')
<div class="dashboard-wrapper container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h5><i class="bi bi-telephone-plus"></i> نوع المتصل</h5>
        <button class="caller-type-btn" data-type="student">
            <i class="bi bi-person-circle me-2"></i> طالب
        </button>
        <button class="caller-type-btn" data-type="parent">
            <i class="bi bi-people-fill me-2"></i> ولي أمر
        </button>
        <button class="caller-type-btn" data-type="staff">
            <i class="bi bi-briefcase-fill me-2"></i> موظف
        </button>
        <button class="caller-type-btn" data-type="external">
            <i class="bi bi-person me-2"></i> خارجي
        </button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h4 class="mb-4"><i class="bi bi-telephone-inbound"></i> تسجيل مكالمة جديدة</h4>

        {{-- بيانات الطالب --}}
        <div id="studentForm" style="display:none;">
            <div class="section-title">بيانات الطالب</div>
            <div class="row mb-3">
                <div class="col-lg-8 col-md-7 mb-3">
                    <label class="form-label">رقم الطالب</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="studentId" placeholder="رقم الطالب">
                        <button class="btn btn-primary" id="searchStudentBtn"><i class="bi bi-search"></i></button>
                    </div>
                </div>
                <div class="col-lg-4 col-md-5 mb-3">
                    <label class="form-label">رقم التذكرة (اختياري)</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="ticketNumber" placeholder="رقم التذكرة">
                        <button class="btn btn-outline-secondary" id="searchTicketBtn"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </div>

            {{-- معلومات الطالب --}}
            <div id="studentInfo" class="student-info" style="display:none;">
                <h6>المعلومات الأكاديمية</h6>
                <div class="row mt-2">
                    <div class="col-md-3 col-6 mb-2"><span class="info-label">الاسم:</span> <span id="studName">-</span></div>
                    <div class="col-md-3 col-6 mb-2"><span class="info-label">الحالة:</span> <span id="studStatus">-</span></div>
                    <div class="col-md-3 col-6 mb-2"><span class="info-label">المعدل:</span> <span id="studCGPA">-</span></div>
                    <div class="col-md-3 col-6 mb-2"><span class="info-label">التخصص:</span> <span id="studMajor">-</span></div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-3 col-6 mb-2"><span class="info-label">الفصل:</span> <span id="studSemester">-</span></div>
                    <div class="col-md-3 col-6 mb-2"><span class="info-label">الكلية:</span> <span id="studFaculty">-</span></div>
                    <div class="col-md-3 col-6 mb-2"><span class="info-label">GPA:</span> <span id="studGPA">-</span></div>
                    <div class="col-md-3 col-6 mb-2"><span class="info-label">دفعة:</span> <span id="studBatch">-</span></div>
                </div>
            </div>

            {{-- سجل التذاكر --}}
            <div id="ticketsHistory" class="mt-3" style="display:none;">
                <h6 class="section-title">سجل البلاغات</h6>
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>رقم</th>
                            <th>التاريخ</th>
                            <th>الحالة</th>
                            <th>الأولوية</th>
                            <th>الفئة</th>
                            <th>رابط</th>
                        </tr>
                    </thead>
                    <tbody id="ticketsTableBody">
                        <tr><td colspan="6" class="text-center">لا توجد بيانات</td></tr>
                    </tbody>
                </table>
            </div>

            {{-- تفاصيل التذكرة --}}
            <div id="ticketInfo" class="ticket-info" style="display:none;">
                <h6>معلومات التذكرة</h6>
                <p><b>الرقم:</b> <span id="ticketId">-</span> | <b>الحالة:</b> <span id="ticketStatus">-</span> | 
                <b>الأولوية:</b> <span id="ticketPriority">-</span> | <b>التاريخ:</b> <span id="ticketDate">-</span></p>
                <p><b>الفئة:</b> <span id="ticketCategory">-</span> | <a href="#" id="ticketLink">رابط التذكرة</a></p>
            </div>

            {{-- تفاصيل المكالمة --}}
            <div class="section-title mt-4">تفاصيل المكالمة</div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">فئة المكالمة</label>
                    <select class="form-select" id="callCategory">
                        <option value="">اختر</option>
                        <option value="academic">أكاديمية</option>
                        <option value="technical">تقنية</option>
                        <option value="administrative">إدارية</option>
                        <option value="other">أخرى</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">أولوية المكالمة</label>
                    <select class="form-select" id="callPriority">
                        <option value="medium">متوسطة</option>
                        <option value="low">منخفضة</option>
                        <option value="high">عالية</option>
                        <option value="urgent">عاجلة</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">الوصف</label>
                <textarea class="form-control" id="callDescription" rows="4"></textarea>
            </div>
            <div class="text-end">
                <button class="btn btn-secondary" id="resetFormBtn">إلغاء</button>
                <button class="btn btn-success" id="saveCallBtn">حفظ</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const callerTypeBtns = document.querySelectorAll('.caller-type-btn');
    const studentForm = document.getElementById('studentForm');
    const ticketsHistory = document.getElementById('ticketsHistory');
    const ticketsTableBody = document.getElementById('ticketsTableBody');

    callerTypeBtns.forEach(btn => {
        btn.addEventListener('click', function(){
            callerTypeBtns.forEach(b=>b.classList.remove('active'));
            this.classList.add('active');
            studentForm.style.display = (this.dataset.type==='student')?'block':'none';
        });
    });

    // جلب بيانات الطالب
    document.getElementById('searchStudentBtn').addEventListener('click',()=>{
        let id=document.getElementById('studentId').value.trim();
        if(!id){alert('أدخل رقم الطالب');return;}
        fetch(`/calls/search-student?stud_id=${id}`).then(r=>r.json()).then(data=>{
            if(!data||data.message){alert('لا يوجد طالب');return;}
            document.getElementById('studName').textContent=data.stud_name+' '+(data.stud_surname||'');
            document.getElementById('studStatus').textContent=data.status_code||'-';
            document.getElementById('studCGPA').textContent=data.stud_cgpa||'-';
            document.getElementById('studMajor').textContent=data.major_code||'-';
            document.getElementById('studSemester').textContent=data.curr_sem||'-';
            document.getElementById('studFaculty').textContent=data.faculty_code||'-';
            document.getElementById('studGPA').textContent=data.stud_gpa||'-';
            document.getElementById('studBatch').textContent=data.batch||'-';
            document.getElementById('studentInfo').style.display='block';

            // عرض سجل التذاكر
            if(data.tickets && data.tickets.length){
                ticketsTableBody.innerHTML='';
                data.tickets.forEach(t=>{
                    ticketsTableBody.innerHTML+=`
                        <tr>
                            <td>${t.ticket_number}</td>
                            <td>${t.issue_date||'-'}</td>
                            <td>${t.Ticket_status}</td>
                            <td>${t.priority}</td>
                            <td>${t.ticket_category}</td>
                            <td><a href="${t.ticket_url}" target="_blank">رابط</a></td>
                        </tr>`;
                });
                ticketsHistory.style.display='block';
            } else {
                ticketsTableBody.innerHTML='<tr><td colspan="6" class="text-center">لا توجد تذاكر</td></tr>';
                ticketsHistory.style.display='block';
            }
        });
    });

    // جلب بيانات التذكرة
    document.getElementById('searchTicketBtn').addEventListener('click',()=>{
        let t=document.getElementById('ticketNumber').value.trim();
        if(!t){alert('أدخل رقم التذكرة');return;}
        fetch(`/calls/search-ticket?ticket_number=${t}`).then(r=>r.json()).then(data=>{
            if(!data||data.message){alert('لا يوجد بلاغ');return;}
            document.getElementById('ticketId').textContent=data.ticket_number;
            document.getElementById('ticketStatus').textContent=data.Ticket_status;
            document.getElementById('ticketPriority').textContent=data.priority;
            document.getElementById('ticketDate').textContent=data.issue_date;
            document.getElementById('ticketCategory').textContent=data.ticket_category;
            document.getElementById('ticketLink').href=data.ticket_url;
            document.getElementById('ticketInfo').style.display='block';
        });
    });

    // حفظ المكالمة
    document.getElementById('saveCallBtn').addEventListener('click',()=>{
        let active=document.querySelector('.caller-type-btn.active');
        let body={
            customer_type: active?active.dataset.type:'student',
            customer_id: document.getElementById('studentId').value||null,
            ticket_number: document.getElementById('ticketNumber').value||null,
            category: document.getElementById('callCategory').value,
            description: document.getElementById('callDescription').value,
            priority: document.getElementById('callPriority').value
        };
        fetch('/calls/store',{method:'POST',headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
        },body:JSON.stringify(body)}).then(r=>r.json()).then(res=>{
            alert(res.message||'تم الحفظ');location.reload();
        });
    });

    document.getElementById('resetFormBtn').addEventListener('click',()=>location.reload());
});
</script>
@endpush
