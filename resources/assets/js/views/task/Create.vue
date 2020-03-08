<template>
    <v-container>
        <v-card :loading="isProcessing" class="my-5">

            <v-card-title>
                {{ isEdit ? 'Edit task' : 'New task' }}
            </v-card-title>

            <v-card-text>
                <v-form enctype="multipart/form-data" v-model="formValid">
                    <v-row class="pa-3">
                        <v-col cols="12" md="12">
                            <v-textarea
                                :rules="rules.required"
                                label="Task..."
                                v-model="formModel.task"
                                solo
                            />

                            <v-menu
                                :nudge-right="40"
                                max-width="290px"
                                min-width="290px"
                                offset-y
                                transition="scale-transition"
                                v-model="dateMenu"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        :rules="rules.required"
                                        :value="formModel.date"
                                        label="Schedule task..."
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        v-on="on"
                                    />
                                </template>
                                <v-date-picker
                                    @input="dateMenu = false"
                                    locale="en"
                                    max="2200-1-1"
                                    min="2000-1-1"
                                    no-title
                                    v-model="formModel.date"
                                />
                            </v-menu>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <v-divider/>

            <v-card-actions class="pa-5">
                <v-spacer/>
                <v-btn :disabled="!formValid || isProcessing" @click.prevent="save" color="success" text>
                    Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-container>
</template>

<script lang="ts">
    import Vue from "vue";
    import {Component} from "vue-property-decorator";
    import axios from "axios";

    @Component({
        name: "TaskCreate",
    })
    export default class TaskCreate extends Vue {

        public formValid: boolean = false;

        public dateMenu: boolean = false;

        public isProcessing: boolean = false;

        public formModel: any = {};

        public rules: any = {
            required: [
                (v: string) => !!v || "This field is required",
            ],
        };

        public isEdit: boolean = false;

        created(): void {
            if (this.$router.currentRoute.name === 'tasks.edit') {
                this.isEdit = true;

                this.fetchServerData();
            }
        }

        fetchServerData(): void {
            this.isProcessing = true;

            axios
                .get('task/' + this.$router.currentRoute.params.id, {

                })
                .then(response => {
                    if (response && response.data && response.status === 200) {
                        this.formModel = response.data;
                    } else {
                        this.$router.push('/tasks/all');
                    }
                })
                .catch(error => {
                    this.$router.push('/tasks/all');
                })
                .finally(() => this.isProcessing = false);
        }

        save(): void {
            this.isProcessing = true;

            let form;
            if (this.isEdit) {
                form = {
                    task: this.formModel.task,
                    date: this.formModel.date
                };
            } else {
                form = new FormData();
                form.append('task', this.formModel.task);
                form.append('date', this.formModel.date);
            }

            axios
                .request({
                    method: this.isEdit ? 'PUT' : 'POST',
                    url: this.isEdit ? `task/${this.$router.currentRoute.params.id}` : 'task',
                    data: form
                })
                .then(response => {
                    if (response.data && response.status === 200) {
                        //this.$router.push('/tasks');
                    } else {
                        // log error
                    }
                })
                .catch(error => {
                    if (error.response.data) {
                        let errors = "";

                        Object.values(error.response.data.errors).forEach((value: any) => {
                            Object.values(value).forEach((error: any) => {
                                errors += "* " + error + "\n";
                            });
                        });

                    } else {
                    }
                })
                .finally(() => this.isProcessing = false);
        }

    }
</script>
