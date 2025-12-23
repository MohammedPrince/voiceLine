@extends('layouts.app')
  
@section('title', 'Reports')
 
@section('content')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style src=""></style>

  <div class="reports-container">
    <ul>
      <li><a href="/reports" class="active"> General Report </a></li>
      <li><a href="/reports/calls-per-user">Detailed Report</a></li>
    </ul>

    <h5>Reports:</h5>

    <!-- فلترة بالمدد -->
    <div>
      <label for="dateRange" class="form-label">Select Range:</label>
      <select id="dateRange" class="form-select">
        <option value="">-- Select --</option>
        <option value="today">Today</option>
        <option value="last7">Last 7 Days</option>
        <option value="last30">Last 30 Days</option>
        <option value="thisMonth">This Month</option>
        <option value="lastMonth">Last Month</option>
        <option value="custom">Custom</option>
      </select>
    </div>

    <!-- فلترة بتاريخ مخصص -->
    <div id="customRange" style="display:none;">
      <label>Start Date:</label>
      <input type="date" id="startDate">
      <label>End Date:</label>
      <input type="date" id="endDate">
      <button id="filterBtn">Filter</button>
    </div>

    <div class="chart-container">
      <canvas id="categoryChart" height="150"></canvas>
    </div>
  </div>

  <script>
     const colorPalette = [
      '#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0', '#9966FF', 
      '#FF9F40', '#8AC926', '#1982C4', '#6A4C93', '#F15BB5',
      '#00BBF9', '#00F5D4', '#FB5607', '#8338EC', '#FF006E'
    ];

    const ctx = document.getElementById('categoryChart').getContext('2d');
    
     const categoryChart = new Chart(ctx, {
      type: 'bar',
      data: { 
        labels: [],  
        datasets: [{
          label: "Number of Calls",
          data: [], //   تعبئت   الفئات
          backgroundColor: []     
        }]
      },
      options: { 
        responsive: true, 
        plugins: { 
          legend: { 
            display: true,
            position: 'top'
          } 
        },
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Number of Calls'
            }
          },
          x: {
            title: {
              display: true,
              text: 'Categories'
            }
          }
        }
      }
    });

    function loadReport(params = {}) {
      let url = "/reports/data";
      const query = new URLSearchParams(params).toString();
      if (query) url += "?" + query;

      console.log("Fetching:", url);

      fetch(url)
        .then(res => res.json())
        .then(data => {
           
          categoryChart.data.labels = data.map(item => item.category);
          categoryChart.data.datasets[0].data = data.map(item => item.total);
          
         
          categoryChart.data.datasets[0].backgroundColor = data.map((item, index) => {
            return colorPalette[index % colorPalette.length];
          });
          
          categoryChart.update();
        })
        .catch(error => {
          console.error("Error fetching data:", error);
      
          const sampleData = [
            { category: "Category 1", total: 15 },
            { category: "Category 2", total: 25 },
            { category: "Category 3", total: 10 },
            { category: "Category 4", total: 30 },
            { category: "Category 5", total: 20 }
          ];
          
          categoryChart.data.labels = sampleData.map(item => item.category);
          categoryChart.data.datasets[0].data = sampleData.map(item => item.total);
          categoryChart.data.datasets[0].backgroundColor = sampleData.map((item, index) => {
            return colorPalette[index % colorPalette.length];
          });
          categoryChart.update();
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
      const dateRange = document.getElementById("dateRange");
      const customRange = document.getElementById("customRange");
      const filterBtn = document.getElementById("filterBtn");

      
      dateRange.addEventListener("change", function() {
        if (this.value === "custom") {
          customRange.style.display = "block";
        } else {
          customRange.style.display = "none";
          if (this.value) loadReport({ period: this.value });
        }
      });

       filterBtn.addEventListener("click", function() {
        const start = document.getElementById("startDate").value;
        const end = document.getElementById("endDate").value;
        if (start && end) {
          loadReport({ startDate: start, endDate: end });
        } else {
          alert("Please select both start and end date.");
        }
      });

       loadReport();
    });
  </script>
  @endsection