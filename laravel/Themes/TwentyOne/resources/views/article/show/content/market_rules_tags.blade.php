<div class="py-4">
    <article
        class="bg-white pt-6 lg:pl-6 pb-[18px] lg:pr-[18px] rounded-lg flex flex-col gap-6"
        >
        <h4
            class="mt-1 sm:max-w-full text-xl font-semibold text-neutral-4 block"
            >
            Market Rules
        </h4>
        <div class="">
            <div x-data="{ open: false }" class="text-sm text-gray-700">
                <div x-show="!open">
                    <p>
                        South Africa took to the International Court of Justice
                        the allegation that Israel is promoting genocide against
                        Palestinians through actions and omissions in its
                        military onslaught started after Hamas’ attacks of
                        October 7th, 2023. Together with the complaint, South
                        Africa requested the Court to order the suspension of
                        the Israeli military operations
                    </p>
                    <button
                        @click="open = true"
                        class="text-blue-500 hover:text-blue-700"
                        >
                    See More
                    </button>
                </div>
                <div x-show="open">
                    <p>
                        South Africa took to the International Court of Justice
                        the allegation that Israel is promoting genocide against
                        Palestinians through actions and omissions in its
                        military onslaught started after Hamas’ attacks of
                        October 7th, 2023. Together with the complaint, South
                        Africa requested the Court to order the suspension of
                        the Israeli military operations.
                    </p>
                    <p>
                        This market aims to predict if, by March 31st, 2024, the
                        International Court of Justice (ICJ) will recommend to
                        Israel the suspension of military actions in the Gaza
                        Strip, in the scope of the trial of the “Application of
                        the Convention on the Prevention and Punishment of the
                        Crime of Genocide in the Gaza Strip (South Africa v.
                        Israel)” case. The recommendation can be part of a
                        preliminary decision and does not necessarily need to be
                        included in the sentence of the case.
                    </p>
                    <button
                        @click="open = false"
                        class="text-blue-500 hover:text-blue-700"
                        >
                    See Less
                    </button>
                </div>
            </div>
        </div>
        <h4
            class="mt-1 sm:max-w-full text-xl font-semibold text-neutral-4 block"
            >
            Tags
        </h4>
        <div class="flex flex-wrap gap-2 items-center">
            <a
                href="#"
                class="py-2.5 px-5 me-2 mb-2 text-sm bg-opacity-50 text-gray-900 focus:outline-none bg-neutral-2 font-bold rounded-lg border border-gray-200 lg:text-base hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                >#Israel</a
                >
            <a
                href="#"
                class="py-2.5 px-5 me-2 mb-2 text-sm bg-opacity-50 text-gray-900 focus:outline-none bg-neutral-2 font-bold rounded-lg border border-gray-200 lg:text-base hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                >#Israel-hamas war</a
                >
            <a
                href="#"
                class="py-2.5 px-5 me-2 mb-2 text-sm bg-opacity-50 text-gray-900 focus:outline-none bg-neutral-2 font-bold rounded-lg border border-gray-200 lg:text-base hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                >#Palestine</a
                >
        </div>
    </article>
</div>