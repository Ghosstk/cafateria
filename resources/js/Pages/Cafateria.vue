<template>
    <basic-layout>
        <div class="bg-white overflow-hidden rounded-lg shadow-md">
            <div class="px-4 py-5">
                <div class="-ml-4 -mt-4 flex justify-between items-center flex-wrap sm:flex-nowrap">
                    <div class="ml-4 mt-4">
                        <h3 class="text-2xl leading-6 font-medium text-gray-900">
                            Cafatéria - 2021
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            <span class="font-bold">Éves keret:</span> {{ formatNumber(totalLimit) }} Ft.
                            <span class="font-bold">Kategória keret:</span> {{ formatNumber(categoryLimit) }} Ft.
                        </p>
                    </div>
                    <div class="ml-4 mt-4 flex-shrink-0">
                        <a :href="route('cafateria.export.csv')" class="cursor-pointer relative inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <font-awesome-icon size="2x" :icon="['fas','file-csv']"/>
                        </a>
                    </div>
                </div>
            </div>
            <form-error v-if="form" :errors="form.errors"></form-error>
            <form @submit.prevent="submit">
                <div class="">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                Összesen
                                <p :class="totalColor">{{formatNumber(total)}} ft</p>
                            </th>
                            <th v-for="(category, index) in categories" :key="index" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                {{category.name}}
                                <p :class="categoryColor[index]">{{categoryTotal[index]}} ft</p>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(monthRow, rowIndex) in form.data().cafateria" :key="rowIndex" :class="rowIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                            <td class="px-6 py-4 whitespace-nowrap font-lg font-bold text-gray-900 capitalize text-center">
                                {{ monthName(rowIndex) }}
                            </td>
                            <td v-for="(categoryRow, categoryIndex) in monthRow.categories" :key="rowIndex+'-'+categoryIndex" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" min="0" :max="categoryLimit" v-model.number="categoryRow.amount" :id="'amount-' + rowIndex + '-' + categoryIndex" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-3 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0" aria-describedby="price-currency" />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">
                                          Ft
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-4 sm:px-6 flex flex-col items-center">
                    <button :disabled="form.processing" type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <font-awesome-icon class="mr-3" :icon="['fas','save']"/>
                        Mentés
                    </button>
                </div>
            </form>
        </div>
        <notification :show="success" @close="success = false"/>
    </basic-layout>
</template>

<script>
import BasicLayout from "@/Layout/BasicLayout";
import FormError from "../Partials/FormError";
import {useForm} from "@inertiajs/inertia-vue3";
import Notification from "../Partials/Notification";

export default {
    components: {
        Notification,
        FormError,
        BasicLayout
    },
    props: {
        categories: Array,
        monthNames: Array,
        errors: Object,
        cafateria: Object
    },
    data(){
        return {
            totalLimit: 400000,
            categoryLimit: 200000,
            total: 0,
            categoryTotal: [],
            categoryColor: [],
            form: useForm({
                cafateria: []
            }),
            success: false
        }
    },
    methods:{
        formatNumber(number){
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },
        monthName(number){
            return this.monthNames[number].name;
        },
        submit() {
            this.form.clearErrors();

            this.form.post(route('cafateria.store'),{
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => this.success = true
            })
            // this.$inertia.post(,this.form);
        },
        categoryColorFn(value){
            let color = {
                "text-green-900": false,
                "text-red-900": false,
                "font-bold": true
            }

            if (value > this.categoryLimit){
                color['text-red-400'] = true;
            } else if (value <= this.categoryLimit && value > 0){
                color['text-green-400'] = true;
            }
            return color;
        }
    },
    watch:{
        form: {
            handler: function(newValue) {
                let total = new Array(this.categories.length).fill(0);
                let formData = this.form.data().cafateria;

                for (let month in formData){
                    this.form.cafateria[month].categories.forEach((category, index) => {
                        total[index] += category.amount;
                    })
                }

                total.forEach((value, index) => {
                    this.categoryColor[index] = this.categoryColorFn(value);
                });

                this.categoryTotal = total.map( x => this.formatNumber(x));

                this.total = total.reduce((a, b) => { return +a + +b })
            },
            deep: true
        }
    },
    computed: {
        totalColor(){
            let color = {
                "text-green-900": false,
                "text-red-900": false,
                "font-bold": true
            }

            if (this.total > this.totalLimit){
                color['text-red-400'] = true;
            } else if (this.total <= this.totalLimit && this.total > 0){
                color['text-green-400'] = true;
            }

            return color;
        }
    },
    created() {
        let formData = this.form.data();

        this.monthNames.forEach((month) => {
            formData.cafateria.push({
                month: month.month,
                categories: []
            })
        })

        formData.cafateria.forEach((month, index) => {
            // this.form[index] = new Array(this.categories.length).fill(0);
            this.categories.forEach((category) => {
                let amount = 0;

                if (Object.keys(this.cafateria).length && this.cafateria[month['month']+'-'+category.id]){
                    amount = this.cafateria[month['month']+'-'+category.id]['amount'];
                }

                month.categories.push({
                    category: category.id,
                    amount: amount
                })
            })
        })

        this.form = this.$inertia.form(formData)
    }
}
</script>

<style scoped>

</style>
