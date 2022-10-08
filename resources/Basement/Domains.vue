<template>
    <div class="w-full">
        <crud-view
            title="Domains"
            singular="Domain"
            @index="(pageAndLimit) => $store.dispatch('getDomains', pageAndLimit)"
            @execute="onExecute"
            :data="$store.getters.domains"
            :paginator="$store.getters.domainPaginator"
        >
            <template v-slot:data="{ data }">
                <div class="flex flex-col">
                    <div class="text-lg text-left flex justify-between">
                        <div>{{ data.domain }}</div>
                        <div>{{ date(data?.expires_at) }}</div>
                    </div>
                    <div class="text-xs flex  flex-wrap gap-2">
                        <div class="flex gap-1 items-center">
                            <svg v-if="data.is_auto_renewing" class="text-green-500 dark:text-green-400 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <svg v-else class="text-red-500 dark:text-red-400 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{data.is_auto_renewing? 'will auto renew':'wont auto renew'}}
                            :form="form"
                        </div>
                        <div class="flex gap-1 items-center">
                            <svg v-if="!data.is_expired" class="text-green-500 dark:text-green-400 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <svg v-else class="text-red-500 dark:text-red-400 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{data.is_expired? 'HAS EXPIRED':'not expired'}}
                        </div>
                        <div class="flex gap-1 items-center">
                            <svg v-if="data.is_namecheap_dns" class="text-green-500 dark:text-green-400 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <svg v-else class="text-red-500 dark:text-red-400 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{data.is_namecheap_dns? 'using namecheap dns':'not using namecheap dns'}}
                        </div>

                        <div class="flex gap-1 items-center">
                            <svg v-if="data.has_whois_guard" class="text-green-500 dark:text-green-400 w-4 h-4"  fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg><svg v-else class="text-red-500 dark:text-red-400 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <svg v-else class="text-red-500 dark:text-red-400 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{data.has_whois_guard? 'uses whois guard':'DOESNT use whois guard'}}
                        </div>
                    </div>
                </div>
            </template>
            <template #modal-title>Add to your greenhouse</template>
            <template #form>
                <div class="flex flex-col gap-4 mt-2">
                  
                </div>
            </template>

            <template #no-data>No domains in your greenhouse</template>
        </crud-view>
    </div>
</template>
<!-- 
I want to build a real time dashboard for my greenhouse.
I'll assume that the server will need to listen on an MQTT topic for the data.
And this website will need it to be converted to websocket.
-->
<script>
export default {
    data() {
        return {
        }
    },
    methods: {
        date(value) {
            return dayjs(value).format('LLL');
        },
        hasErrors(error) {
            if (!this.form.errors) {
                return '';
            }

            return this.form.errors[error];
        },
        async onExecute({ actionToRun, selectedItems}) {
            try {
                await this.$store.dispatch('executeAction', {
                    url: actionToRun.url,
                    data: {
                        domains: selectedItems.map(item => item.id)
                    },
                });
                Spork.toast('Success! Running action...');

            } catch (e) {
                Spork.toast(e.message, 'error');
            }
        },
    }
}
</script>

<style scoped>

</style>
