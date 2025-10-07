@extends('layouts.admin')

@section('title', get_phrase('Audit Reports List'))

@section('admin_layout')


<style>
  .badge-dark {
    color: var(--whiteColor);
    text-align: center;
    font-size: 12px;
    font-style: normal;
    font-weight: 500;
    line-height: 100%;
    padding: 5px 10px;
    border-radius: 4px;
    background: #242D47;
    width: max-content;
}
</style>
<div class="ol-card radius-8px">
    <div class="ol-card-body my-2 py-12px px-20px">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-settings-sliders me-2"></i>
                {{ get_phrase('Audit Reports Lists') }}
            </h4>          
        </div>
    </div>
</div>

<div class="ol-card mt-3">
   <div class="ol-card-body p-3">
    @if(!empty($auditreports) && count($auditreports)!=0)
    <form action="{{ route('admin.auditreports') }}" method="GET" class="mb-3">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <div class="d-flex gap-3 mb-3">           
            <div><strong>Total Paid:</strong> ₹{{ number_format($totalPaid, 0) }}</div>
            <div><strong>Remaining Amount:</strong> ₹{{ number_format($totalRemaining, 0) }}</div>
            <div><strong>Cancelled Amount:</strong> ₹{{ number_format($totalCancelled, 0) }}</div>
            <div><strong>Total Amount:</strong> ₹{{ number_format($totalAmount, 0) }}</div>
        </div>
            <div class="d-flex align-items-center gap-1">
                <label class="fw-semibold me-1">Date:</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control form-control-sm" style="width:150px;">
                <span class="mx-1">to</span>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control form-control-sm" style="width:150px;">
            </div>
            <button type="submit" class="btn btn-primary btn-sm px-3">Filter</button>        
        </div>
    </form>

    <div class="d-flex justify-content-between my-2 flex-wrap align-items-center gap-2">
    <div class="d-flex align-items-center gap-1">
        <label>Show</label>
        <select id="rowsPerPage" class="form-select form-select-sm" style="width:80px;">
            <option value="5">5</option>
            <option value="10" selected>10</option>
            <option value="20">20</option>
            <option value="50">50</option>
        </select>
        <label>entries</label>
    </div>
  <div>
    <button onclick="exportPDF()" class="btn btn-danger btn-sm me-2">
        <i class="fas fa-file-pdf me-1"></i> Export PDF
    </button>
    <button onclick="exportExcel()" class="btn btn-success btn-sm">
        <i class="fas fa-file-excel me-1"></i> Export Excel
    </button>
</div>


    <input type="text" id="searchInput" placeholder="Search..." class="form-control" style="width:200px;">

    
</div>

    <table id="appointments-table" class="table table-bordered table-striped w-100">
        <thead>
            <tr>
                <th>Id</th>
                <th>Customer</th>
                <th>Contact</th>
                <th>Property</th>
                <th>Segment Type</th>
                <th>Total Price</th>
                <th>Person</th>
                <th>Booking Date</th>
                <th>Details</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($auditreports as $key => $appointment)
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $appointment['customer_name'] ?? '' }}</td>
                <td>{{ $appointment['customer_phone'] ?? '' }}</td>
                <td>
                    @php
                    $property = [];
                    if (!empty($appointment['room_name'])) {
                        $roomNames = is_array($appointment['room_name']) ? $appointment['room_name'] : explode(',', $appointment['room_name']);
                        $property = array_merge($property, $roomNames);
                    }
                    if (!empty($appointment['menu_summary'])) {
                        $menuItems = is_array($appointment['menu_summary']) ? $appointment['menu_summary'] : explode(',', $appointment['menu_summary']);
                        $property = array_merge($property, $menuItems);
                    }
                    $property = array_filter(array_map('trim', $property));
                    @endphp
                    {{ implode(', ', $property) }}
                </td>
                <td>{{ $appointment['listing_type'] ?? '' }}</td>
                <td>{{ $appointment['total_price'] ?? '' }}</td>
                <td>{{ (!empty($appointment['adults']) ? 'Adults: '.$appointment['adults'] : '') . ' ' . (!empty($appointment['child']) ? 'Child: '.$appointment['child'] : '') }}</td>
                <td>{{ $appointment['date'] ?? '' }}</td>
                <td>{{ \Illuminate\Support\Str::limit($appointment['message'] ?? '', 40) }}</td>
                <td>{{ $appointment['status'] == 1 ? 'Successfully Ended' : ($appointment['status'] == 3 ? 'Cancelled' : 'Not started yet') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>



<div id="pagination" class="my-2"></div>

    @else
        @include('layouts.no_data_found')
    @endif
</div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">



<script>
    const table = document.getElementById('appointments-table');
const tbody = table.querySelector('tbody');
const searchInput = document.getElementById('searchInput');
const rowsSelect = document.getElementById('rowsPerPage');

let currentPage = 1;
let rowsPerPage = parseInt(rowsSelect.value);

// Convert tbody rows to array
let allRows = Array.from(tbody.querySelectorAll('tr'));
let filteredRows = allRows;

// --- Render Table Page ---
function renderTablePage(page = 1, rows = filteredRows) {
    tbody.innerHTML = '';
    let start = (page - 1) * rowsPerPage;
    let end = start + rowsPerPage;
    let pageRows = rows.slice(start, end);
    pageRows.forEach(r => tbody.appendChild(r));
    renderPagination(rows.length, page);
}

// --- Render Pagination Buttons ---
function renderPagination(totalRows, page = 1) {
    const paginationDiv = document.getElementById('pagination');
    paginationDiv.innerHTML = '';
    let totalPages = Math.ceil(totalRows / rowsPerPage);
    for (let i = 1; i <= totalPages; i++) {
        let btn = document.createElement('button');
        btn.textContent = i;
        btn.className = 'btn btn-sm me-1 ' + (i === page ? 'btn-primary' : 'btn-secondary');
        btn.onclick = () => { currentPage = i; renderTablePage(currentPage, filteredRows); }
        paginationDiv.appendChild(btn);
    }
}

// --- Search Function ---
searchInput.addEventListener('input', () => {
    let query = searchInput.value.toLowerCase();
    filteredRows = allRows.filter(row => row.textContent.toLowerCase().includes(query));
    currentPage = 1;
    renderTablePage(currentPage, filteredRows);
});

// --- Rows per page change ---
rowsSelect.addEventListener('change', () => {
    rowsPerPage = parseInt(rowsSelect.value);
    currentPage = 1;
    renderTablePage(currentPage, filteredRows);
});

// --- Sort Function ---
table.querySelectorAll('th').forEach((th, index) => {
    th.style.cursor = 'pointer';
    th.addEventListener('click', () => {
        let asc = !th.asc;
        th.asc = asc;
        filteredRows.sort((a, b) => {
            let aText = a.children[index].textContent.trim();
            let bText = b.children[index].textContent.trim();
            return asc ? aText.localeCompare(bText, undefined, {numeric: true}) : bText.localeCompare(aText, undefined, {numeric: true});
        });
        renderTablePage(currentPage, filteredRows);
    });
});

// --- Initial Render ---
renderTablePage(currentPage, allRows);

// --- Export PDF ---
function exportPDF() {
    const { jsPDF } = window.jspdf;
    let doc = new jsPDF('landscape');
    let data = [];
    filteredRows.forEach(row => {
        let rowData = Array.from(row.children).map(td => td.textContent);
        data.push(rowData);
    });
    doc.autoTable({ head: [[...Array.from(table.querySelectorAll('th')).map(th => th.textContent)]], body: data });
    doc.save('audit_reports.pdf');
}

// --- Export Excel ---
function exportExcel() {
    let wb = XLSX.utils.book_new();
    wb.Props = {
        Title: "Audit Reports",
        Subject: "Audit Reports List",
        Author: "Admin",
        CreatedDate: new Date()
    };
    let ws_data = [];
    let headers = Array.from(table.querySelectorAll('th')).map(th => th.textContent);
    ws_data.push(headers);
    filteredRows.forEach(row => {
        let rowData = Array.from(row.children).map(td => td.textContent);
        ws_data.push(rowData);
    });
    let ws = XLSX.utils.aoa_to_sheet(ws_data);
    XLSX.utils.book_append_sheet(wb, ws, "AuditReports");
    XLSX.writeFile(wb, "AuditReports.xlsx");
}

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

@endsection