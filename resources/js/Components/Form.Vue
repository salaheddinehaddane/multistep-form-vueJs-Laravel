<template>
    <div class="container my-4">
        <div class="row">
            <div class="col-xl-8 col-md-8 col-sm-12 mx-auto">
                <form @submit.prevent="submit()">
                    <div class="card">
                        <div class="card-header">
                            <div class="progress" role="progressbar" aria-label="Info striped example"
                                :aria-valuenow="progress" aria-valuemin="0" :aria-valuemax="data.length">
                                <div class="progress-bar progress-bar-striped bg-info"
                                    :style="{ width: (progress * 100) / data.length + '%' }">
                                </div>
                            </div>
                        </div>
                        <div :class="{ 'd-block': index == 0, 'd-none': index != 0 }" v-for="item, index in data"
                            :key="item.id" :id="item.id">
                            <div class="card-body">
                                <div class="form-check">
                                    <p class="text-center">
                                        {{ item.question }}
                                    </p>
                                    <div v-for="option in item.options">
                                        <input v-model="inputs[item.id]" class="form-check-input" type="radio"
                                            :name="item.id" :value="option" :id="option">
                                        <label class="form-check-label" :for="option">{{ option }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button v-if="index != 0" @click="action('back', item.id)" type="button"
                                    class="btn btn-outline-danger mx-2">
                                    Back
                                </button>
                                <button v-if="index != (data.length - 1)" :disabled="this.inputs[item.id] === undefined"
                                    @click="action('next', item.id)" type="button" class="btn btn-outline-primary mx-2">
                                    Next
                                </button>
                                <button v-if="index === (data.length - 1)" type="submit" class="btn btn-success mx-2">
                                    Send
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</template>


<script>

export default {
    data() {
        return {
            data: {},
            progress: 1,
            inputs: {},
        }
    },
    mounted() {
        this.fetchData();
    },
    methods: {
        action(e, id) {
            if (e == 'next') {
                this.progress++;
                let nextId = parseInt(id) + 1;
                document.getElementById(nextId.toString()).classList.remove('d-none');
                document.getElementById(id).classList.add('d-none');
            } else {
                this.progress--;
                let lastId = parseInt(id) - 1;
                document.getElementById(lastId.toString()).classList.remove('d-none');
                document.getElementById(id).classList.add('d-none');
            }

        },
        fetchData() {
            axios.get('storage/data/data.json').then(
                response => {
                    this.data = response.data;
                }
            )
                .catch(error => {
                    console.log("Fetching data error " + error);
                })
        },
        validateForm() {
            //check if all inputs are filled
            for (let key in this.data) {
                if (this.inputs[parseInt(key) + 1] === undefined) return false;
            }
            return true;
        },
        submit() {
            if (this.validateForm()) {
                axios.post('/api/submit', this.inputs)
                    .then(response => {
                        console.log('response: ' + response.data);
                        alert('Form submitted successfully');
                    })
                    .catch(error => {
                        console.log("submit form error " + error);
                    })
            }
            else {
                alert('Please fill all the fields');
            }
        }
    }
}

</script>