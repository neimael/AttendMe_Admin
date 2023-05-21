<template>
    <div>
      <canvas ref="chartCanvas"></canvas>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { Chart, registerables } from 'chart.js';
  import { ref, onMounted } from 'vue';
  Chart.register(...registerables);
  
  export default {
    mounted() {
      this.fetchPresenceCounts();
    },
    methods: {
      fetchPresenceCounts() {
        axios
          .get('/api/line_chart_presence')
          .then(response => {
            const presenceCounts = response.data;
            this.renderChart(presenceCounts);
          })
          .catch(error => {
            console.error(error);
          });
      },
      renderChart(presenceCounts) {
        const ctx = this.$refs.chartCanvas.getContext('2d');
        const currentYear = new Date().getFullYear();
  
        // Sort the months in ascending order
        const sortedMonths = Object.keys(presenceCounts).sort();
  
        const chartData = {
          labels: sortedMonths,
          datasets: [
            {
              label: 'Presence Count',
              data: sortedMonths.map(month => presenceCounts[month]),
              backgroundColor: '#6262fc',
              borderColor: '#0000ff',
              borderWidth: 1,
              fill: true,
            },
          ],
        };
  
        new Chart(ctx, {
          type: 'line',
          data: chartData,
          options: {
            responsive: true,
            scales: {
              x: {
                display: true,
              },
              y: {
                display: true,
                suggestedMax: Math.max(...Object.values(presenceCounts)),
                suggestedMin: 0,
                precision: 0,
              },
            },
            plugins: {
              title: {
                display: true,
                text: `Count of Presences Monthly for ${currentYear}`,
                color: '#ffffff',
                font: {
                  size: 16,
                },
              },
            },
            
          },
        });
      },
    },
  };
  </script>
  