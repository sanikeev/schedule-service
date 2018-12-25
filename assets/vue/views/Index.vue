<template>
    <div class="container">
        <div class="container">
            <div class="row" style="margin: 15px">
                <form>
                    <div class="form-row">
                        <div class="col">
                            <datepicker v-model="query.start" @input="filter()" format="yyyy-MM-dd"
                                        placeholder="From"></datepicker>
                        </div>
                        <div class="col">
                            <datepicker v-model="query.end" @input="filter()" format="yyyy-MM-dd"
                                        placeholder="To"></datepicker>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <table class="table">
                <tr>
                    <th>#</th>
                    <th>Курьер</th>
                    <th>Город</th>
                    <th>Начало</th>
                    <th>Окончание</th>
                </tr>
                <tr v-for="(s, key) in list">
                    <td>{{ key }}</td>
                    <td>{{ s.courier }}</td>
                    <td>{{ s.city }}</td>
                    <td>{{ s.started_at }}</td>
                    <td>{{ s.ended_at }}</td>
                </tr>
            </table>
            <nav>
                <paginate
                        :page-count="totalPages"
                        :click-handler="paginate"
                        :prev-text="'Prev'"
                        :next-text="'Next'"
                        :container-class="'pagination'"
                        :page-link-class="'page-link'"
                        :prev-class="'age-item'"
                        :prev-link-class="'page-link'"
                        :next-class="'age-item'"
                        :next-link-class="'page-link'"
                        :page-class="'page-item'">
                </paginate>
            </nav>
        </div>
    </div>
</template>

<script>
  import scheduleService from '../services/schedule'
  import Datepicker from 'vuejs-datepicker';
  import Paginate from 'vuejs-paginate'
  import moment from 'moment'


  export default {
    name: "Index",
    components: {Datepicker, Paginate},
    data: function () {
      return {
        list: [],
        totalPages: 0,
        pages: [],
        query: {
          start: (new Date()).setMonth((new Date()).getMonth() - 1),
          end: new Date(),
          page: 1
        }
      }
    },
    beforeMount() {
      scheduleService.list(this.query).then(data => {
        this.list = data.data
        this.totalPages = data.totalPages
        for (let index = 1; index <= data.totalPages; index++) {
          this.pages.push(index);
        }
      })
    },
    methods: {
      paginate(page) {
        this.query.page = page
        scheduleService.list(this.query).then(data => {
          this.list = data.data
          this.totalPages = data.totalPages
        })
      },
      filter() {
        let start = this.query.start.getTime();
        let end = this.query.end.getTime();
        if (start > end) {
          this.query.start = this.query.end
        }
        scheduleService.list(this.query).then(data => {
          this.list = data.data
          this.totalPages = data.totalPages
        })
      }
    }
  }
</script>

<style scoped>

</style>