<template>
  <card :title="month_year_title" class="mb-3">
    <div class="row">
      <div class="col">
        <strong>Add Event</strong>

        <div class="form-group">
          <label>Event Name</label>
          <input 
            type="text" 
            v-model="event_name"
            class="form-control" 
            placeholder="Event name">
        </div>

        <div class="form-group">
          <label>Date Range</label>
          <Calendar 
            v-model="range" 
            :range="true" 
            lang="en" 
            firstDayOfWeek="sunday"/>
        </div>

        <div class="row m-1">
          <div v-for="(day_name, index) in day_names" :key="index" class="col custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" v-model="selected_days[index]" :id="day_name">
            <label class="custom-control-label" :for="day_name">{{ day_name }}</label>
          </div>
        </div>

        <div class="text-center mt-4">
          <button type="button" class="btn btn-primary" @click="updateEvents">Save Events</button>
        </div>

      </div>
      <div class="col">
        <div class="row">
          <div class="col">
            <select class="custom-select" v-model="month" @change="changeCalendar">
              <option v-for="(month, index) in $months" :key="index" :value="month.value">{{ month.month }}</option>
            </select>
          </div>
          <div class="col">
            <select class="custom-select" v-model="year" @change="changeCalendar">
              <option v-for="(year, index) in years" :key="index" :value="year">{{ year }}</option>
            </select>
          </div>
        </div>
        <div style="border-bottom: 1px solid silver; padding: 5px">
          <strong>Date</strong>
          <strong class="float-right">Event</strong>
        </div>
        <div v-for="(day, index) in days" :key="index" :style="`border-bottom: 1px solid silver; padding: 5px; background-color: ${day.style}`">
          <span>{{ day.display_date }}</span>
          <span class="float-right">{{ day.event_name }}</span>
        </div>
      </div>
    </div>
  </card>
</template>

<script>
import moment from 'moment'
import Calendar from 'vue-datepicker-ui'

export default {
  middleware: 'auth',
  components: {
    Calendar
  },
  data() {
    return {
      year: 2020,
      month: 9,
      event_name: '',
      days: [],
      range: [],
      start_range: '',
      end_range: '',
      day_names: [ 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' ],
      selected_days: [false, false, false, false, false, false, false],
      test: true,
      month_events: [],
      years: []
    }
  },
  methods: {
    generateDays (year, month) {
      this.days = []
      let monthIndex = month - 1
      let date = new Date(year, monthIndex, 1)
      while (date.getMonth() == monthIndex) {
        this.days.push(
          { 
            date: moment(date).format('YYYY-MM-DD'), 
            display_date: `${date.getDate()} ${this.day_names[date.getDay()]}`,
            event_name: '',
            style: 'white'
          }
        )
        date.setDate(date.getDate() + 1)
      }
    },
    generateInitialRange () {
      this.start_range = moment(new Date()).startOf('month'),
      this.end_range = moment(new Date()).endOf('month')
      this.range = [this.start_range, this.end_range]
    },
    getMonthEvents (year, month) {
      axios.get('/api/calendar-events', {
          params: {
            year: this.year,
            month: this.month
          }
        })
        .then(response => {
          this.month_events = response.data
          for (let i = 0; i < this.month_events.length; i++) {
            let day_search = _.find(this.days, { date: this.month_events[i].date })
            if (day_search) {
              for (let j = 0; j < this.days.length; j++) {
                if (this.days[j].date === day_search.date) {
                  this.days[j].event_name = this.month_events[i].event_name
                  this.days[j].style = '#99ff99'
                }
              }
            }
          }
        })
        .catch(error => {
          console.log(error);
        })
    },
    updateEvents () {
      for (let i = 1; i < 2; i++) {
        this.range[i] = moment(this.range[i]).add(1, 'days').utc().format('MM/DD/YYYY')
      }
      axios.post('/api/calendar-events', {
          range: this.range,
          event_name: this.event_name,
          selected_days: this.selected_days
        })
        .then(response => {
          this.changeCalendar()

          Toast.fire({
            icon: 'success',
            title: 'Event successfully updated.'
          })
        })
        .catch(error => {
          console.log(error)
        })
    },
    loadYears () {
      let max = new Date().getFullYear() + 5
      let min = max - 9
      for (let i = max; i >= min; i--) {
        this.years.push(i)
      }
    },
    changeCalendar () {
      this.generateDays(this.year, this.month)
      this.getMonthEvents(this.year, this.month)
    }
  },
  created () {
    this.generateDays(this.year, this.month)
    this.generateInitialRange()
    this.getMonthEvents()
    this.loadYears()
  },
  computed: {
    month_year_title () {
      return moment(`${this.year}-${this.month}`, 'YYYY-M').format('MMMM YYYY')
    }
  }
}
</script>

<style>
.card-header {
  text-align: center !important;
  font-weight: bold !important;
  font-size: 25px !important;
}
</style>