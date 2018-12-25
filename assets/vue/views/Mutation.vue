<template>
    <div>
        <div class="row">
            <div class="alert alert-danger" role="alert" v-if="errorBusy">
                {{ errorBusy }}
            </div>
            <form @submit.prevent="submit()">
                <div class="form-group row">
                    <label for="region" class="col-sm-4 col-md-4 col-form-label text-right">Регион</label>
                    <div class="col-sm-6 col-md-6">
                        <select class="form-control" id="region" v-model="form.city" @change="checkArrival()">
                            <option disabled value="">Выберите регион</option>
                            <option v-for="c in cities" v-bind:value="c.id">{{c.name}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="courier" class="col-sm-4 col-md-4 col-form-label text-right">ФИО курьера</label>
                    <div class="col-sm-6 col-md-6">
                        <select class="form-control" id="courier" v-model="form.courier">
                            <option disabled value="">Выберите курьера</option>
                            <option v-for="c in couriers" v-bind:value="c.id">{{c.name}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="date" class="col-sm-4 col-md-4 col-form-label text-right">Дата выезда</label>
                    <div class="col-sm-6 col-md-6">
                        <datepicker v-model="form.date" @input="checkArrival()" format="yyyy-MM-dd" id="date"></datepicker>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="arrival" class="col-sm-4 col-md-4 col-form-label text-right">Срок доставки</label>
                    <div class="col-sm-6 col-md-6">
                        <input type="text" class="form-control" id="arrival" disabled v-model="arrival">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-md-4 col-form-label"></label>
                    <div class="col-sm-6 col-md-6">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
  import scheduleService from '../services/schedule';
  import courierService from '../services/courier';
  import cityService from '../services/city'
  import Datepicker from 'vuejs-datepicker';
  import moment from 'moment';

  export default {
    name: "Mutation",
    components: { Datepicker },
    data: function () {
      return {
        form: {
          courier: {},
          city: {},
          date: new Date(),
        },
        arrival: null,
        couriers: [],
        cities: [],
        errorBusy: null
      }
    },
    beforeCreate() {
      cityService.list().then(data => {
        this.cities = data
      });
      courierService.list().then(data => {
        this.couriers = data
      })

    },
    methods: {
      submit() {
        scheduleService.check({courier: this.form.courier, date: moment(this.form.date).format('YYYY-MM-DD')}).then(data => {
          if (data.check){
            this.errorBusy = 'На текущую дату этот курьер занят';
            return;
          }
          this.errorBusy = null;
          this.form.started_at = moment(this.form.date).format('YYYY-MM-DD');
          scheduleService.create(this.form).then(data => {
            location.replace('/');
          })
        })
      },
      checkArrival(){
        if (JSON.stringify(this.form.city) === JSON.stringify({}) || !this.form.date) {
          return;
        }
        scheduleService.arrival({city: this.form.city, date: moment(this.form.date).format('YYYY-MM-DD')}).then(data => {
          this.arrival = data.arrival_date
        })
      }
    }
  }
</script>

<style scoped>
    form {
        width: 100%
    }
</style>