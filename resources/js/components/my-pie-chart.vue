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
    this.fetchPresenceData();
  },
  methods: {
    fetchPresenceData() {
      axios.get('/api/pie_chart_presence')
        .then(response => {
          const statusCounts = response.data;
          this.renderPieChart(statusCounts);
        })
        .catch(error => {
          console.error('Error fetching presence data:', error);
        });
    },
    renderPieChart(statusCounts) {
      const chartCanvas = this.$refs.chartCanvas;

      const chartData = new Chart(chartCanvas, {
        type: 'pie',
        data: {
          labels: Object.keys(statusCounts),
          datasets: [
            {
              data: Object.values(statusCounts),
              backgroundColor: ['#ff0000', '#00ff00', '#0000ff'], // Customize colors as needed
            },
          ],
        },
        options: {
          // Customize chart options as needed
        },
      });

      // Store the chart instance in a ref for further usage
      this.chartData = ref(chartData);
    },
  },
};
</script>
