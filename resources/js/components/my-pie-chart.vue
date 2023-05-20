<template>
    <div>
      <canvas ref="chart"></canvas>
    </div>
  </template>
  
  <script>
 import { Doughnut } from 'vue-chartjs';
  
  export default {
    extends: Doughnut,
    props: ['chartData', 'options'],
    mounted() {
      this.renderChart(this.chartData, this.options);
    }
  };
  </script>
  