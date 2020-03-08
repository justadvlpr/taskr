<template>
    <v-container>

        <v-card class="ma-4">
            <v-card-title>
                {{ $router.currentRoute.name === 'tasks.all' ? 'All tasks' : 'My tasks for today' }}
            </v-card-title>

            <v-data-table
                :headers="headers"
                :hide-default-footer="true"
                :loading="isLoading"
                :items="items"
                :items-per-page="-1"
                sort-by="id"
            >
                <template v-slot:item.action="{ item }">
                    <v-btn
                        :to="'/tasks/' + item.id + '/edit'"
                        icon
                        text
                    >
                        <v-icon>mdi-square-edit-outline</v-icon>
                    </v-btn>

                    <v-btn
                        @click="deleteItem(item)"
                        icon
                        text
                    >
                        <v-icon>mdi-delete</v-icon>
                    </v-btn>

                    <v-btn
                        @click="completed(item)"
                        icon
                        text
                        v-if="item.completed === 0 && $router.currentRoute.name === 'tasks.today'"
                    >
                        <v-icon>mdi-check-circle</v-icon>
                    </v-btn>
                </template>

                <template v-slot:item.completed="{ item }">
                    <v-chip v-if="item.completed === 0" color="red" outlined small>
                        Not completed
                    </v-chip>
                    <v-chip v-if="item.completed === 1" color="green" outlined small>
                        Completed
                    </v-chip>
                </template>
            </v-data-table>
        </v-card>

    </v-container>
</template>

<script lang="ts">
    import Vue from "vue";
    import Component from "vue-class-component";
    import axios from "axios";
    import {Watch} from "vue-property-decorator";

    @Component({
        name: "TaskIndex",
    })
    export default class TaskIndex extends Vue {

        public headers: Array<any> = [
            {
                text: 'ID',
                value: 'id',
                sortable: false,
            },
            {
                text: 'Task',
                value: 'task',
                sortable: false,
            },
            {
                text: 'Date',
                value: 'date',
                sortable: false,
            },
            {
                text: 'Status',
                value: 'completed',
                sortable: false,
            },

            // actions

            {
                text: 'Actions',
                value: 'action',
                sortable: false,
                searchable: false,
                width: '210px'
            },
        ];

        public isLoading: boolean = true;

        public items: Array<any> = [];

        mounted(): void {
            this.init();
        }

        init() {
            this.isLoading = true;

            window.scrollTo({top: 0, left: 0, behavior: 'smooth'});

            axios
                .get('task', {
                    params: {
                        filter: this.$router.currentRoute.name === 'tasks.all' ? 'all' : 'today'
                    }
                })
                .then((response: any) => {
                    if (response && response.data && response.status === 200) {
                        this.items = response.data.tasks;
                    }
                })
                .finally(() => this.isLoading = false);
        }

        completed(item: any) {
            this.isLoading = true;

            axios
                .put(`task/${item.id}`, {
                    completed: 1,
                })
                .then((response: any) => {
                    if (response && response.data && response.status === 200) {
                        item.completed = 1;
                    }
                })
                .finally(() => this.isLoading = false);
        }

        deleteItem(item: any) {
            if (!confirm('Are your sure you want to delete this task?')) {
                return false;
            }

            this.isLoading = true;

            axios
                .delete(`task/${item.id}`)
                .then((response) => {
                    if (response && response.status === 204) {
                        this.items = this.items.filter(function (ele) {
                            return ele !== item;
                        });
                    }
                })
                .catch(() => console.log('could not delete task'))
                .finally(() => this.isLoading = false);
        }

    }
</script>
