<template>
    <div class="container">
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
        </div>
    </div>
</template>

<script>
    import scheduleService from '../services/schedule'
  export default {
    name: "Index",
    data: function () {
      return {
        list: [],
        totalPages: null,
        pages: []
      }
    },
    beforeMount() {
      scheduleService.list({}).then(data => {
        this.list = data.data
        this.totalPages = data.totalPages
        for (let index = 1; index <= data.totalPages; index++) {
          this.pages.push(index);
        }
      })
    },
    methods: {
        paginate() {

        }
    }
  }
</script>

<style scoped>

</style>