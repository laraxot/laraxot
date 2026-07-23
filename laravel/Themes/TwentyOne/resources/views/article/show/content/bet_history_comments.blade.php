<div class="py-4">
    <div class="container mx-auto p-4 bg-white">
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul
                class="flex flex-wrap -mb-px text-sm font-medium text-center"
                id="default-tab"
                data-tabs-toggle="#default-tab-content"
                role="tablist"
                >
                <li class="me-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 rounded-t-lg"
                        id="profile-tab"
                        data-tabs-target="#profile"
                        type="button"
                        role="tab"
                        aria-controls="profile"
                        aria-selected="false"
                        >
                    Bet History
                    </button>
                </li>
                <li class="me-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                        id="dashboard-tab"
                        data-tabs-target="#dashboard"
                        type="button"
                        role="tab"
                        aria-controls="dashboard"
                        aria-selected="false"
                        >
                    Comments
                    </button>
                </li>
            </ul>
        </div>
        <div id="default-tab-content">
            <div
                class="hidden p-4 rounded-lg bg-white dark:bg-gray-800"
                id="profile"
                role="tabpanel"
                aria-labelledby="profile-tab"
                >
                <div class="flex flex-col">
                    <div
                        class="flex flex-wrap lg:flex-nowrap items-center gap-4 justify-between mb-4"
                        >
                        <!-- SEARCH -->
                        <div
                            class="relative w-1/3 border-none bg-neutral-2 py-1.5 rounded-full pr-10"
                            >
                            <input
                                type="text"
                                class="w-auto outline-none bg-transparent border-none px-3 py-3 text-[12px] lg:text-sm focus:outline-none"
                                placeholder="Search markets and outcomes"
                                />
                            <button
                                type="button "
                                class="absolute rounded-r-full bg-neutral-2 inset-y-0 right-0 px-3 py-2 text-gray-500 focus:outline-none"
                                >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-6"
                                    width="32"
                                    height="32"
                                    viewBox="0 0 24 24"
                                    >
                                    <path
                                        fill="currentColor"
                                        d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 0 0 1.48-5.34c-.47-2.78-2.79-5-5.59-5.34a6.505 6.505 0 0 0-7.27 7.27c.34 2.8 2.56 5.12 5.34 5.59a6.5 6.5 0 0 0 5.34-1.48l.27.28v.79l4.25 4.25c.41.41 1.08.41 1.49 0c.41-.41.41-1.08 0-1.49zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14"
                                        />
                                </svg>
                            </button>
                        </div>
                        <div
                            class="flex flex-wrap lg:flex-nowrap items-center gap-2"
                            >
                            <div class="min-w-max">
                                <button
                                    id="dropdownDelayButton"
                                    data-dropdown-toggle="dropdownDelay"
                                    data-dropdown-delay="500"
                                    data-dropdown-trigger="hover"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-1.5 py-2 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                    type="button"
                                    >
                                    Play Money
                                    <svg
                                        class="w-2.5 h-2.5 ms-3 ml-2"
                                        aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 10 6"
                                        >
                                        <path
                                            stroke="currentColor"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="m1 1 4 4 4-4"
                                            />
                                    </svg>
                                </button>
                                <!-- Dropdown menu -->
                                <div
                                    id="dropdownDelay"
                                    class="z-10 left-0 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-64 dark:bg-gray-700"
                                    >
                                    <ul
                                        class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                        aria-labelledby="dropdownDelayButton"
                                        >
                                        <li>
                                            <a
                                                href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                                >All</a
                                                >
                                        </li>
                                        <li>
                                            <a
                                                href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                                >Play Money</a
                                                >
                                        </li>
                                        <li>
                                            <a
                                                href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                                >Real Money</a
                                                >
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div
                                class="flex gap-2 items-center flex-wrap lg:flex-nowrap"
                                >
                                <button
                                    type="button"
                                    class="py-2 px-3 me-2 text-[12px] lg:text-sm min-w-max font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-900 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                    >
                                Only people I follow
                                </button>
                                <button
                                    type="button"
                                    class="py-2 px-5 me-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-900 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                    >
                                My Bets
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto rounded-md">
                        <table class="w-full text-sm text-left table-auto">
                            <thead
                                class="text-[12px] font-medium uppercase bg-white"
                                >
                                <tr class="*text-black">
                                    <th class="px-4 py-2">Date</th>
                                    <th class="px-4 py-2">Action</th>
                                    <th class="px-4 py-2">User</th>
                                    <th class="px-4 py-2">Outcome</th>
                                    <th class="px-4 py-2">Av. Share Price</th>
                                    <th class="px-4 py-2">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="*:py-4 *:px-4">
                                    <td class="">
                                        <div
                                            class="flex flex-col text-sm gap-2 font-light"
                                            >
                                            <div class="font-normal">02/16/2024</div>
                                            <p class="text-neutral-3">01h44</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <button
                                            type="button"
                                            class="bg-blue-700 text-blue-700 bg-opacity-20 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-3xl text-sm px-4 py-2 text-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                            >
                                        Bet
                                        </button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex gap-2 items-center relative">
                                            <div
                                                class="h-6 w-6 rounded-full flex items-center justify-center bg-neutral-5 text-neutral-1 font-bold"
                                                >
                                                B
                                            </div>
                                            <span
                                                class="w-3/4 truncate text-[#4c626d] text-[1em]"
                                                >
                                            billythec...
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="">No</div>
                                    </td>
                                    <td class="px-4 py-2">~5</td>
                                    <td class="px-4 py-2">
                                        10
                                        <span>ø</span>
                                    </td>
                                </tr>
                                <tr class="*:py-4 *:px-4">
                                    <td class="">
                                        <div
                                            class="flex flex-col text-sm gap-2 font-light"
                                            >
                                            <div class="font-normal">02/16/2024</div>
                                            <p class="text-neutral-3">01h44</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <button
                                            type="button"
                                            class="bg-blue-700 text-blue-700 bg-opacity-20 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-3xl text-sm px-4 py-2 text-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                            >
                                        Bet
                                        </button>
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex gap-2 items-center relative">
                                            <div
                                                class="h-6 w-6 rounded-full flex items-center justify-center bg-neutral-5 text-neutral-1 font-bold"
                                                >
                                                B
                                            </div>
                                            <span
                                                class="w-3/4 truncate text-[#4c626d] text-[1em]"
                                                >
                                            billythec...
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="">No</div>
                                    </td>
                                    <td class="px-4 py-2">~5</td>
                                    <td class="px-4 py-2">
                                        10
                                        <span>ø</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div
                class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800"
                id="dashboard"
                role="tabpanel"
                aria-labelledby="dashboard-tab"
                >
                <div class="flex flex-col">
                    <button
                        type="button"
                        class="text-white mb-4 w-full bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-4 lg:text-lg me-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                        >
                    Login
                    </button>
                    <div class="flex gap-4">
                        <div
                            class="flex-none h-12 w-12 overflow-hidden rounded-full relative"
                            >
                            <img
                                class="absolute h-full w-full object-cover"
                                src="https://static.My_Company.com/desktop-web/static/media/picture.38b291e63ecc13b00e50.svg"
                                alt=""
                                />
                        </div>
                        <div class="">
                            <h4
                                class="inline-flex gap-2 mb-4 font-bold items-center"
                                >
                                <span>My_Company Bot</span>
                                <span class="font-light text-neutral-3"
                                    >22 days</span
                                    >
                            </h4>
                            <div class="text-neutral-3 space-y-2">
                                <p>
                                    South Africa took to the Internacional Court of
                                    Justice the allegation that Israel is promoting
                                    genocide against Palestinians through actions and
                                    omissions in its military onslaught started after
                                    Hamas’ attacks of October 7th, 2023. Together with
                                    the complaint, South Africa requested the Court to
                                    order the suspension of the Israeli military
                                    operations.
                                </p>
                                <p>
                                    <strong
                                        >This market aims to predict if, by March 31st,
                                    2024, the International Court of Justice (ICJ)
                                    will recommend to Israel the suspension of
                                    military actions in the Gaza Strip, in the scope
                                    of the trial of the
                                    <a
                                        href="%E2%80%9Dhttps%3A//www.icj-cij.org/case/192%E2%80%9D"
                                        rel="ugc noopener nofollow"
                                        target="_blank"
                                        class="text-blue-1"
                                        >
                                    “Application of the Convention on the
                                    Prevention and Punishment of the Crime of
                                    Genocide in the Gaza Strip (South Africa v.
                                    Israel)” case</a
                                        ></strong
                                        >. The recommendation can be part of a preliminary
                                    decision and does not necessarily need to be
                                    included in the sentence of the case.
                                </p>
                                <p>
                                    See the current crowd-generated prediction, and
                                    make your own bet:
                                    <a
                                        href="https://My_Company.com/q/190131"
                                        rel="ugc noopener nofollow"
                                        class="text-blue-1"
                                        target="_blank"
                                        >Will the International Court of Justice (ICJ)
                                    ask Israel to suspend its actions in Gaza by the
                                    end of March?</a
                                        >
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
