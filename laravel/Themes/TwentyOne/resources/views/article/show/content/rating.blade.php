<div class="py-4">
    <template x-if="true">
        <div class="flex flex-col gap-5">
            <article class="bg-white pt-6 lg:pl-6 pb-[18px] lg:pr-[18px] rounded-lg flex flex-col gap-6">
                <div class="pl-6 lg:pl-0">

                <!-- outcomes -->
                    <div
                        class="flex flex-nowrap overflow-x-auto lg:grid lg:grid-cols-3 gap-2 pl-6 lg:pl-0"
                        >
                        <!-- Outcome Yes -->
                        <div
                            x-on:click="selection='Yes'"
                            class="p-1.5 flex flex-col justify-between gap-10 w-[128px] lg:w-auto min-w-[128px] isolate relative overflow-hidden rounded-lg group/outcome"
                            >
                            <div class="absolute inset-0 -z-[1]">
                                <img
                                    class="object-cover h-full w-full"
                                    alt="Yes"
                                    title="Yes"
                                    src="https://My_Company-media-production.s3.amazonaws.com/cache/5f/97/5f97a5a9ea6a404ce35c310c3571a03f.jpg"
                                    sizes="144px"
                                    />
                                <div
                                    class="absolute inset-0 group-hover/outcome:bg-blue-1/50"
                                    ></div>
                            </div>
                            <div
                                class="bg-neutral-5 h-8 w-11 rounded-sm flex items-center justify-center text-white"
                                >
                                <span>60%</span>
                            </div>
                            <p class="text-sm font-medium text-white leading-[1.1]">
                                Yes
                            </p>
                        </div>
                        <!-- Outcome No -->
                        <div
                            x-on:click="selection='No'"
                            class="p-1.5 flex flex-col justify-between gap-10 w-[128px] lg:w-auto min-w-[128px] isolate relative overflow-hidden rounded-lg group/outcome"
                            >
                            <div class="absolute inset-0 -z-[1]">
                                <img
                                    class="object-cover h-full w-full"
                                    alt="No"
                                    title="No"
                                    src="https://My_Company-media-production.s3.amazonaws.com/cache/73/a3/73a3e4925cc28db4a7be86ab79355246.jpg"
                                    sizes="144px"
                                    />
                                <div
                                    class="absolute inset-0 group-hover/outcome:bg-blue-1/50"
                                    ></div>
                            </div>
                            <div
                                class="bg-neutral-5 h-8 w-11 rounded-sm flex items-center justify-center text-white"
                                >
                                <span>40%</span>
                            </div>
                            <p class="text-sm font-medium text-white leading-[1.1]">
                                No
                            </p>
                        </div>
                    </div>
                </div>

                <div class="px-6 lg:px-0 flex items-center justify-between">
                    <!-- Placeholder for interactive elements such as tooltips -->
                </div>
            </article>
        </div>
    </template>
</div>
