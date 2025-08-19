<template>
    <GameLayout>
        <LoadingComponent :isLoading="isLoading">
            <div class="text-center">
                <span></span>
            </div>
        </LoadingComponent>

        <div class="w-full bg-container min-h-[calc(100vh-80px)] p-[10px] pt-[0px] px-[16px] bg-[#0a0d0b]">
            <div class="bg-[#171717] rounded-[16px] min-h-[80svh] w-full px-5 pt-6 md:pt-7 md:pb-12 ">

                <div class="max-w-7xl mx-auto">
                    <CompradoresAoVivo />
                    <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-4 py-3 pt-[0px] mt-5">
                        <div class="col-span-1 md:col-span-4" v-if="!isAuthenticated">
                            <div class="relative mb-4">
                                <div>
                                    <div
                                        class="absolute inset-0 z-30 flex flex-col items-center justify-center bg-black/75 backdrop-blur-[2px]">
                                        <div class="mb-3 text-center flex flex-col items-center justify-center">
                                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                                class="h-20 stroke-slate-200">
                                                <path d="M11.6801 14.62L14.2401 12.06L11.6801 9.5" stroke-width="1.5"
                                                    stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round">
                                                </path>
                                                <path d="M4 12.0601H14.17" stroke-width="1.5" stroke-miterlimit="10"
                                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M12 4C16.42 4 20 7 20 12C20 17 16.42 20 12 20" stroke-width="1.5"
                                                    stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round">
                                                </path>
                                            </svg>
                                            <div class="text-white/70 text-lg font-semibold mb-2 drop-shadow">Faça login pra
                                                jogar</div>
                                        </div>
                                        <button @click="openRegisterModal"
                                            class="px-6 py-2 rounded button-primary text-black hover:bg-green-600 hover:text-white transition text-sm">
                                            Registrar </button>
                                    </div>
                                    <div class="relative grid grid-cols-3 gap-3 w-full sm:w-fit max-w-[90vw] sm:max-w-md bg-secondary border-slate-700/20 rounded-lg border-2 shadow-sm p-1.5 border-surface overflow-hidden aspect-square"
                                        style="min-width: 270px; min-height: 390px;">
                                        <div class="flex items-center justify-center rounded-lg shadow group border-2 bg-gray-900/70 overflow-hidden relative transition-all duration-300 border-slate-700/20"
                                            style="width: 100%; aspect-ratio: 1 / 1; min-height: 0px; min-width: 0px; display: flex; align-items: center; justify-content: center;">
                                            <img :src="`/assets/images/cover-scratch.png`" alt="prêmio"
                                                class="max-w-[60%] max-h-[60%] object-contain drop-shadow" loading="lazy">
                                        </div>
                                        <div class="flex items-center justify-center rounded-lg shadow group border-2 bg-gray-900/70 overflow-hidden relative transition-all duration-300 border-slate-700/20"
                                            style="width: 100%; aspect-ratio: 1 / 1; min-height: 0px; min-width: 0px; display: flex; align-items: center; justify-content: center;">
                                            <img :src="`/assets/images/cover-scratch.png`" alt="prêmio"
                                                class="max-w-[60%] max-h-[60%] object-contain drop-shadow" loading="lazy">
                                        </div>
                                        <div class="flex items-center justify-center rounded-lg shadow group border-2 bg-gray-900/70 overflow-hidden relative transition-all duration-300 border-slate-700/20"
                                            style="width: 100%; aspect-ratio: 1 / 1; min-height: 0px; min-width: 0px; display: flex; align-items: center; justify-content: center;">
                                            <img :src="`/assets/images/cover-scratch.png`" alt="prêmio"
                                                class="max-w-[60%] max-h-[60%] object-contain drop-shadow" loading="lazy">
                                        </div>
                                        <div class="flex items-center justify-center rounded-lg shadow group border-2 bg-gray-900/70 overflow-hidden relative transition-all duration-300 border-slate-700/20"
                                            style="width: 100%; aspect-ratio: 1 / 1; min-height: 0px; min-width: 0px; display: flex; align-items: center; justify-content: center;">
                                            <img :src="`/assets/images/cover-scratch.png`" alt="prêmio"
                                                class="max-w-[60%] max-h-[60%] object-contain drop-shadow" loading="lazy">
                                        </div>
                                        <div class="flex items-center justify-center rounded-lg shadow group border-2 bg-gray-900/70 overflow-hidden relative transition-all duration-300 border-slate-700/20"
                                            style="width: 100%; aspect-ratio: 1 / 1; min-height: 0px; min-width: 0px; display: flex; align-items: center; justify-content: center;">
                                            <img :src="`/assets/images/cover-scratch.png`" alt="prêmio"
                                                class="max-w-[60%] max-h-[60%] object-contain drop-shadow" loading="lazy">
                                        </div>
                                        <div class="flex items-center justify-center rounded-lg shadow group border-2 bg-gray-900/70 overflow-hidden relative transition-all duration-300 border-slate-700/20"
                                            style="width: 100%; aspect-ratio: 1 / 1; min-height: 0px; min-width: 0px; display: flex; align-items: center; justify-content: center;">
                                            <img :src="`/assets/images/cover-scratch.png`" alt="prêmio"
                                                class="max-w-[60%] max-h-[60%] object-contain drop-shadow" loading="lazy">
                                        </div>
                                        <div class="flex items-center justify-center rounded-lg shadow group border-2 bg-gray-900/70 overflow-hidden relative transition-all duration-300 border-slate-700/20"
                                            style="width: 100%; aspect-ratio: 1 / 1; min-height: 0px; min-width: 0px; display: flex; align-items: center; justify-content: center;">
                                            <img :src="`/assets/images/cover-scratch.png`" alt="prêmio"
                                                class="max-w-[60%] max-h-[60%] object-contain drop-shadow" loading="lazy">
                                        </div>
                                        <div class="flex items-center justify-center rounded-lg shadow group border-2 bg-gray-900/70 overflow-hidden relative transition-all duration-300 border-slate-700/20"
                                            style="width: 100%; aspect-ratio: 1 / 1; min-height: 0px; min-width: 0px; display: flex; align-items: center; justify-content: center;">
                                            <img :src="`/assets/images/cover-scratch.png`" alt="prêmio"
                                                class="max-w-[60%] max-h-[60%] object-contain drop-shadow" loading="lazy">
                                        </div>
                                        <div class="flex items-center justify-center rounded-lg shadow group border-2 bg-gray-900/70 overflow-hidden relative transition-all duration-300 border-slate-700/20"
                                            style="width: 100%; aspect-ratio: 1 / 1; min-height: 0px; min-width: 0px; display: flex; align-items: center; justify-content: center;">
                                            <img :src="`/assets/images/cover-scratch.png`" alt="prêmio"
                                                class="max-w-[60%] max-h-[60%] object-contain drop-shadow" loading="lazy">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 w-full">
                                <button type="button" @click="openRegisterModal"
                                    class="button-primary hover:text-white rounded-lg px-4 md:px-8 py-2 flex gap-2 items-center text-black"><i
                                        class="fa-solid fa-user-plus"></i><span>Registrar</span></button>
                                <button type="button"
                                    class="w-[120px] h-[46px] flex flex-col justify-center rounded-lg transition-all duration-200 bg-[#262626] hover:bg-gray-700">
                                    <i class="fa-solid fa-bolt-lightning text-[#fff]"></i>
                                </button>
                                <button type="button" @click="openRegisterModal"
                                    class="text-white h-[46px] w-full bg-[#2a2a2a] flex gap-2 items-center justify-center rounded-lg text-sm"><i
                                        class="fa-solid fa-arrows-retweet"></i><span class="hidden md:inline">Rodada
                                        Automática</span><span class="md:hidden inline">Auto</span></button>
                            </div>
                        </div>
    
                        <div class="col-span-1 md:col-span-4" v-if="isAuthenticated">
                            <div class="relative mb-4">
                                <div>
                                    <div class="relative grid grid-cols-3 gap-3 w-full sm:w-fit max-w-[90vw] sm:max-w-md bg-secondary border-slate-700/20 rounded-lg border-2 shadow-sm p-1.5 border-surface overflow-hidden aspect-square"
                                        style="min-width: 270px; min-height: 390px;">
                                        <!-- Overlay quando não comprou ainda -->
                                        <div v-if="!hasPurchased && !isGameComplete" 
                                            class="absolute inset-0 z-30 flex flex-col items-center justify-center bg-black/80 backdrop-blur-sm rounded-lg">
                                            <div class="text-center">
                                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                                    class="h-16 w-16 stroke-yellow-400 mb-4 mx-auto">
                                                    <path d="M12 2C12.5523 2 13 2.44772 13 3V4.10002C16.9502 4.56329 20 7.90002 20 12C20 16.1 16.9502 19.4367 13 19.9V21C13 21.5523 12.5523 22 12 22C11.4477 22 11 21.5523 11 21V19.9C7.04978 19.4367 4 16.1 4 12C4 7.90002 7.04978 4.56329 11 4.10002V3C11 2.44772 11.4477 2 12 2Z" stroke-width="2"/>
                                                    <path d="M12 7V13L15 15" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                <div class="text-white text-lg font-semibold mb-2">
                                                    Compre sua cartela
                                                </div>
                                                <div class="text-white/70 text-sm">
                                                    Clique em "Comprar" para começar a raspar
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div v-for="(item, index) in gameItems" :key="index" 
                                            class="flex items-center justify-center rounded-lg shadow group border-2 bg-gray-900/70 overflow-hidden relative transition-all duration-300"
                                            :class="(item.isWinning && isGameComplete) ? 'border-green-500 ring-2 ring-green-400' : 'border-slate-700/20'"
                                            style="width: 100%; aspect-ratio: 1 / 1; min-height: 0px; min-width: 0px; display: flex; align-items: center; justify-content: center;">
                                            <div class="flex flex-col items-center justify-center">
                                                <img :src="item.image" :alt="item.name" class="max-w-[60%] max-h-[50%] object-contain drop-shadow mb-1" loading="lazy">
                                                <span v-if="item.type === 'money'" class="text-xs text-green-400 font-bold">{{ item.value }}</span>
                                                <span v-else class="text-xs text-white/60 font-medium">{{ item.value }}</span>
                                            </div>
                                        </div>
                                        <canvas
                                            ref="scratchCanvas"
                                            class="absolute inset-0 z-20 rounded-lg touch-none select-none transition-opacity duration-500"
                                            :style="{ display: hasPurchased ? 'block' : 'none' }"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 w-full">
                                <button type="button" @click="buyNewCard"
                                    class="button-primary text-black hover:text-white rounded-lg px-2 py-2 flex gap-2 items-center transition duration-300"
                                    :class="hasPurchased && !isGameComplete ? 'hidden' : ''"
                                    :disabled="!isGameComplete && hasPurchased">
                                    <i class="fa-solid fa-coin-vertical"></i><span>Comprar</span>
                                        <span class="bg-gray-900 rounded-lg px-2 py-1 text-sm text-white">
                                            <span class="text-green-500 mr-1">R$</span>
                                            <span>{{this.game?.valor ?? 'Carregando...'}}</span>
                                        </span>
                                </button>
                                <button type="button" @click="toggleAutoReveal" v-if="hasPurchased && !isGameComplete"
                                    class="w-[120px] h-[46px] flex items-center justify-center gap-1 rounded-lg transition-all duration-200"
                                    :class="autoRevealEnabled ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-800 hover:bg-gray-700'">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 stroke-slate-50">
                                        <path
                                            d="M6.08998 13.28H9.17998V20.48C9.17998 22.16 10.09 22.5 11.2 21.24L18.77 12.64C19.7 11.59 19.31 10.72 17.9 10.72H14.81V3.52002C14.81 1.84002 13.9 1.50002 12.79 2.76002L5.21998 11.36C4.29998 12.42 4.68998 13.28 6.08998 13.28Z"
                                            stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                    </svg>
                                    <span class="text-xs text-white" v-if="autoRevealEnabled"></span>
                                </button>

                                <button type="button" @click="demoMode"
                                    class="w-[120px] h-[46px] flex items-center justify-center rounded-lg transition-all duration-200"
                                    :class="demoGameEnabled ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-800 hover:bg-gray-700'">
                                    <span class="text-xs text-white" v-if="!demoGameEnabled">REAL</span>
                                    <span class="text-xs text-white" v-if="demoGameEnabled" :disabled="hasPurchased ? 'disabled' : false">DEMO</span>
                                </button>

                                <button type="button" @click="toggleAutoPlay"
                                    class="text-white h-[46px] w-full flex gap-2 items-center justify-center rounded-lg text-sm transition-all duration-200"
                                    :class="autoPlayEnabled ? 'bg-green-600 hover:bg-green-700' : 'bg-[#2a2a2a] hover:bg-gray-700'">
                                    <i class="fa-solid fa-arrows-retweet" :class="autoPlayEnabled ? 'animate-spin' : ''"></i>
                                    <span class="hidden md:inline">{{ autoPlayEnabled ? 'Parar Auto' : 'Rodada Automática' }}</span>
                                    <span class="md:hidden inline">{{ autoPlayEnabled ? 'Parar' : 'Auto' }}</span>
                                </button>
                            </div>
                        </div>
    
                        <div class="col-span-1 md:col-span-8">
                            <div class="space-y-5">
                                <div class="rounded-lg border border-gray-600/30 hidden md:block"><img alt=""
                                        class="rounded-lg"
                                        src="https://raspadinha-api.venix.app/storage/raspadinhas/covers/01K1160ZJV1PNVPR9GQXCVWVWV.png">
                                </div>
                                <div
                                    class="text-card-foreground flex flex-col gap-[20px] rounded-xl border border-gray-600/30 shadow-sm p-4 flex-1">
                                    <span class="flex text-2xl items-center text-white">
                                        <svg width="1em" height="1em" fill="none" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg" class="inline size-6 mr-2">
                                            <path fill="currentColor" fill-rule="evenodd"
                                                d="M1.5 6.375c0-1.036.84-1.875 1.875-1.875h17.25c1.035 0 1.875.84 1.875 1.875v3.026a.75.75 0 0 1-.375.65 2.249 2.249 0 0 0 0 3.898.75.75 0 0 1 .375.65v3.026c0 1.035-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 17.625v-3.026a.75.75 0 0 1 .374-.65 2.249 2.249 0 0 0 0-3.898.75.75 0 0 1-.374-.65zm15-1.125a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-1.5 0V6a.75.75 0 0 1 .75-.75Zm.75 4.5a.75.75 0 0 0-1.5 0v.75a.75.75 0 0 0 1.5 0zm-.75 3a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-1.5 0v-.75a.75.75 0 0 1 .75-.75Zm.75 4.5a.75.75 0 0 0-1.5 0V18a.75.75 0 0 0 1.5 0zM6 12a.75.75 0 0 1 .75-.75H12a.75.75 0 0 1 0 1.5H6.75A.75.75 0 0 1 6 12Zm.75 2.25a.75.75 0 0 0 0 1.5h3a.75.75 0 0 0 0-1.5z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Centavo da Sorte
                                    </span>
                                    <p class="text-sm text-[#a1a1a1]"> Pressione <kbd
                                            class="pointer-events-none inline-flex h-5 select-none items-center gap-1 rounded border border-[#262626] bg-[#262626] px-1.5 font-mono text-[10px] font-medium text-[#a1a1a1] opacity-100"><span
                                                class="text-xs">Ctrl</span>R</kbd>
                                        para comprar. </p>
                                    <p class="text-sm text-[#a1a1a1]"> Pressione <kbd
                                            class="pointer-events-none inline-flex h-5 select-none items-center gap-1 rounded border border-[#262626] bg-[#262626] px-1.5 font-mono text-[10px] font-medium text-[#a1a1a1] opacity-100"><span
                                                class="text-xs">Ctrl</span>B</kbd>
                                        para revelar. </p>
                                    <p class="text-sm text-[#a1a1a1]"> Pressione <kbd
                                            class="pointer-events-none inline-flex h-5 select-none items-center gap-1 rounded border border-[#262626] bg-[#262626] px-1.5 font-mono text-[10px] font-medium text-[#a1a1a1] opacity-100"><span
                                                class="text-xs">Ctrl</span>B</kbd>
                                        para revelar rápido. </p>
                                </div>
                                <div class="w-full flex gap-4 rounded-lg border border-gray-600/30 p-4">
                                    <div>
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            class="h-[16px] fill-[#fff]">
                                            <path
                                                d="M22.0001 5.36519L22.7501 5.36519V5.36519H22.0001ZM9.4546 13.3029L9.98381 12.7714L9.4546 13.3029ZM9.4546 8.54376L9.98381 9.07521L9.4546 8.54376ZM15.4286 14.4926L14.8994 13.9612L15.4286 14.4926ZM10.6494 14.4926L10.1202 15.0241L10.6494 14.4926ZM20.5154 9.42733L21.0446 9.95878L20.5154 9.42733ZM22.0001 5.85801L21.2501 5.85801V5.85801H22.0001ZM14.5413 3.47846L14.0121 2.94701V2.94701L14.5413 3.47846ZM13.4905 16.0931L13.2315 15.3892L13.4905 16.0931ZM7.8623 10.4352L8.55803 10.7153L7.8623 10.4352ZM19.9862 8.89589L14.8994 13.9612L15.9578 15.0241L21.0446 9.95878L19.9862 8.89589ZM11.1786 13.9612L9.98381 12.7714L8.92539 13.8343L10.1202 15.0241L11.1786 13.9612ZM9.98381 9.07521L15.0705 4.00991L14.0121 2.94701L8.9254 8.01231L9.98381 9.07521ZM18.1257 2.75H18.6207V1.25H18.1257V2.75ZM21.2501 5.36519L21.2501 5.85801L22.7501 5.85802L22.7501 5.36519L21.2501 5.36519ZM18.6207 2.75C19.4383 2.75 19.9764 2.75158 20.3752 2.80497C20.7527 2.85552 20.8912 2.93987 20.976 3.02427L22.0344 1.96137C21.6242 1.55295 21.117 1.3909 20.5743 1.31824C20.0528 1.24842 19.3961 1.25 18.6207 1.25V2.75ZM22.7501 5.36519C22.7501 4.5933 22.7517 3.93861 22.6815 3.41858C22.6083 2.87664 22.445 2.37023 22.0344 1.96137L20.976 3.02427C21.0603 3.10823 21.1444 3.24483 21.195 3.61928C21.2485 4.01563 21.2501 4.55072 21.2501 5.36519H22.7501ZM9.98381 12.7714C9.40553 12.1956 9.02646 11.8158 8.78266 11.4976C8.55202 11.1966 8.51489 11.0409 8.51489 10.9233H7.01489C7.01489 11.5027 7.25938 11.9759 7.59198 12.4099C7.91142 12.8269 8.3772 13.2884 8.92539 13.8343L9.98381 12.7714ZM10.1202 15.0241C10.6684 15.57 11.1319 16.0338 11.5505 16.3518C11.9865 16.683 12.4601 16.9252 13.039 16.9252V15.4252C12.918 15.4252 12.7601 15.387 12.4579 15.1574C12.1382 14.9145 11.7569 14.537 11.1786 13.9612L10.1202 15.0241ZM21.0446 9.95878C21.7336 9.27266 22.2359 8.79124 22.4996 8.15734L21.1147 7.58125C20.9925 7.87498 20.7623 8.12304 19.9862 8.89589L21.0446 9.95878ZM21.2501 5.85801C21.2501 6.95105 21.2367 7.28785 21.1147 7.58125L22.4996 8.15734C22.7634 7.5231 22.7501 6.82826 22.7501 5.85801H21.2501ZM15.0705 4.00991C15.8463 3.23743 16.0958 3.00759 16.3919 2.88546L15.82 1.49878C15.1845 1.76089 14.7015 2.26053 14.0121 2.94701L15.0705 4.00991ZM18.1257 1.25C17.1507 1.25 16.4551 1.23682 15.82 1.49878L16.3919 2.88546C16.6884 2.76318 17.0287 2.75 18.1257 2.75V1.25ZM14.8994 13.9612C14.4537 14.4051 14.1218 14.735 13.8374 14.9802C13.5518 15.2266 13.3678 15.3391 13.2315 15.3892L13.7494 16.797C14.138 16.654 14.4843 16.4032 14.8172 16.1161C15.1513 15.8279 15.526 15.4541 15.9578 15.0241L14.8994 13.9612ZM13.2315 15.3892C13.1613 15.415 13.1008 15.4252 13.039 15.4252V16.9252C13.2865 16.9252 13.522 16.8806 13.7494 16.797L13.2315 15.3892ZM8.9254 8.01231C8.50388 8.43205 8.13652 8.79732 7.85074 9.12306C7.56633 9.44725 7.31657 9.78252 7.16658 10.1551L8.55803 10.7153C8.61357 10.5773 8.73196 10.3931 8.97831 10.1123C9.2233 9.83306 9.54933 9.50786 9.98381 9.07521L8.9254 8.01231ZM7.16658 10.1551C7.06832 10.3991 7.01489 10.6534 7.01489 10.9233H8.51489C8.51489 10.8585 8.52641 10.7938 8.55803 10.7153L7.16658 10.1551Z">
                                            </path>
                                            <path opacity="0.5"
                                                d="M7.70886 10.2825L8.23887 9.75182L8.23807 9.75103L7.70886 10.2825ZM11.4377 6.79466C11.7861 7.01864 12.2501 6.91776 12.4741 6.56932C12.6981 6.22089 12.5972 5.75686 12.2488 5.53288L11.4377 6.79466ZM11.2958 5.81187L10.8902 6.44276L10.8902 6.44276L11.2958 5.81187ZM9.06024 4.66617L9.16976 3.92421L9.06024 4.66617ZM4.79597 7.23425L5.32517 7.76571H5.32517L4.79597 7.23425ZM7.50536 4.86464L7.79249 5.5575L7.50536 4.86464ZM5.35102 8.45319L5.62702 7.75582L5.62702 7.75582L5.35102 8.45319ZM5.45822 8.49603L5.17282 9.1896L5.45822 8.49603ZM6.87303 9.45016L7.40224 8.91872H7.40224L6.87303 9.45016ZM6.79108 9.3691L6.26913 9.90769L6.79108 9.3691ZM5.0216 8.32282L4.74561 9.02019L4.74561 9.02019L5.0216 8.32282ZM13.735 16.3379L13.205 16.8685C13.2166 16.8801 13.2286 16.8913 13.2409 16.9021L13.735 16.3379ZM18.4484 11.7064C18.223 11.3589 17.7586 11.2598 17.4111 11.4851C17.0635 11.7104 16.9644 12.1748 17.1897 12.5224L18.4484 11.7064ZM18.1724 12.6595L17.5431 13.0675V13.0675L18.1724 12.6595ZM19.323 14.8857L20.0648 14.7752L19.323 14.8857ZM16.744 19.132L17.2732 19.6634L17.2732 19.6634L16.744 19.132ZM19.1237 16.434L19.8157 16.7232L19.1237 16.434ZM15.6917 19.0096L16.3882 18.7315L15.6917 19.0096ZM15.2791 18.0608L14.6217 18.4218L15.2791 18.0608ZM14.1899 16.7362L14.7191 16.2048L14.7021 16.1878L14.684 16.172L14.1899 16.7362ZM14.9015 17.4891L15.4915 17.0262L14.9015 17.4891ZM16.6709 19.2048L16.1417 18.6733L16.1417 18.6733L16.6709 19.2048ZM14.0204 15.5629C13.7273 15.2702 13.2524 15.2704 12.9597 15.5635C12.667 15.8566 12.6673 16.3315 12.9604 16.6242L14.0204 15.5629ZM7.33218 10.9663C7.62525 11.259 8.10013 11.2587 8.39284 10.9656C8.68556 10.6726 8.68527 10.1977 8.3922 9.90496L7.33218 10.9663ZM12.2488 5.53288L11.7014 5.18098L10.8902 6.44276L11.4377 6.79466L12.2488 5.53288ZM11.7014 5.18098C11.162 4.83425 10.722 4.55077 10.3423 4.34645C9.95322 4.13704 9.58257 3.98514 9.16976 3.92421L8.95073 5.40813C9.1263 5.43404 9.32436 5.50204 9.63143 5.6673C9.94793 5.83764 10.3315 6.08359 10.8902 6.44276L11.7014 5.18098ZM5.32517 7.76571C5.81542 7.27754 6.33923 6.75615 6.80949 6.32767C7.04443 6.11361 7.25705 5.931 7.43833 5.79144C7.63121 5.64296 7.74622 5.57667 7.79249 5.5575L7.21823 4.17177C6.98214 4.26961 6.73911 4.43673 6.52332 4.60285C6.29593 4.7779 6.04924 4.99111 5.79924 5.21889C5.29965 5.67409 4.75113 6.22049 4.26677 6.70279L5.32517 7.76571ZM9.16976 3.92421C8.5128 3.82724 7.83793 3.91496 7.21823 4.17177L7.79249 5.5575C8.16883 5.40154 8.56995 5.35192 8.95073 5.40813L9.16976 3.92421ZM4.74561 9.02019L5.07503 9.15056L5.62702 7.75582L5.2976 7.62544L4.74561 9.02019ZM6.34382 9.98161L7.17965 10.8139L8.23807 9.75103L7.40224 8.91872L6.34382 9.98161ZM5.07503 9.15056C5.13063 9.17256 5.15208 9.18107 5.17282 9.1896L5.74363 7.80246C5.71136 7.78918 5.67901 7.77639 5.62702 7.75582L5.07503 9.15056ZM7.40224 8.91872C7.36266 8.8793 7.33806 8.85478 7.31302 8.83052L6.26913 9.90769C6.2852 9.92326 6.30154 9.9395 6.34382 9.98161L7.40224 8.91872ZM5.17282 9.1896C5.58092 9.35754 5.95297 9.6013 6.26913 9.90769L7.31302 8.83052C6.86006 8.39155 6.3274 8.04268 5.74363 7.80246L5.17282 9.1896ZM4.26677 6.70279C3.54282 7.42368 3.79893 8.64553 4.74561 9.02019L5.2976 7.62545C5.35173 7.64687 5.36997 7.7211 5.32517 7.76571L4.26677 6.70279ZM17.1897 12.5224L17.5431 13.0675L18.8017 12.2515L18.4484 11.7064L17.1897 12.5224ZM16.2148 18.6005L16.1417 18.6733L17.2001 19.7362L17.2732 19.6634L16.2148 18.6005ZM17.5431 13.0675C17.9038 13.624 18.1507 14.0057 18.3217 14.3207C18.4875 14.6263 18.5553 14.8227 18.5811 14.9961L20.0648 14.7752C20.0034 14.3628 19.8504 13.9928 19.64 13.6052C19.4347 13.2269 19.1499 12.7886 18.8017 12.2515L17.5431 13.0675ZM17.2732 19.6634C17.7576 19.1811 18.3063 18.6349 18.7634 18.1374C18.9922 17.8884 19.2063 17.6428 19.3822 17.4163C19.5489 17.2014 19.7171 16.959 19.8157 16.7232L18.4317 16.1448C18.4127 16.1901 18.3465 16.3043 18.1973 16.4964C18.0572 16.6768 17.8739 16.8885 17.6589 17.1225C17.2286 17.5908 16.705 18.1123 16.2148 18.6005L17.2732 19.6634ZM18.5811 14.9961C18.6373 15.3735 18.5878 15.7712 18.4317 16.1448L19.8157 16.7232C20.074 16.1049 20.1624 15.4312 20.0648 14.7752L18.5811 14.9961ZM14.684 16.172L14.2291 15.7736L13.2409 16.9021L13.6958 17.3005L14.684 16.172ZM16.3882 18.7315C16.2023 18.2657 16.0876 17.9749 15.9365 17.6998L14.6217 18.4218C14.7185 18.598 14.7962 18.7892 14.9951 19.2876L16.3882 18.7315ZM13.6607 17.2677C14.0415 17.6469 14.1872 17.7938 14.3115 17.9521L15.4915 17.0262C15.2977 16.7792 15.0751 16.5593 14.7191 16.2048L13.6607 17.2677ZM15.9365 17.6998C15.8068 17.4635 15.658 17.2383 15.4915 17.0262L14.3115 17.9521C14.4275 18.1001 14.5313 18.2571 14.6217 18.4218L15.9365 17.6998ZM16.1417 18.6733C16.182 18.6332 16.236 18.6242 16.2733 18.6317C16.311 18.6392 16.3638 18.6704 16.3882 18.7315L14.9951 19.2876C15.3559 20.1916 16.5258 20.4077 17.2001 19.7362L16.1417 18.6733ZM14.265 15.8072L14.0204 15.5629L12.9604 16.6242L13.205 16.8685L14.265 15.8072ZM7.17886 10.8131L7.33218 10.9663L8.3922 9.90496L8.23887 9.75182L7.17886 10.8131Z">
                                            </path>
                                            <path
                                                d="M5.5482 13L3.85646 14.6897C3.43177 15.1139 2.74321 15.1139 2.31852 14.6897C1.89383 14.2655 1.89383 13.5778 2.31852 13.1536L2.47231 13M11.0757 18.5696L9.38395 20.2593C8.95926 20.6835 8.95926 21.3712 9.38395 21.7954C9.80864 22.2195 10.4972 22.2195 10.9219 21.7954L11.0757 21.6418M6.93603 17.1588L5.22645 18.8308"
                                                stroke-width="1.5" stroke-linecap="round"></path>
                                            <path opacity="0.5"
                                                d="M10.6329 17.1855C10.9258 16.8926 10.9258 16.4177 10.6329 16.1248C10.34 15.8319 9.86514 15.8319 9.57224 16.1248L10.6329 17.1855ZM7.46982 18.2272C7.17692 18.5201 7.17692 18.995 7.46982 19.2879C7.76271 19.5808 8.23758 19.5808 8.53048 19.2879L7.46982 18.2272ZM7.85719 14.4131C8.15009 14.1202 8.15009 13.6454 7.85719 13.3525C7.5643 13.0596 7.08943 13.0596 6.79653 13.3525L7.85719 14.4131ZM4.67963 15.4694C4.38674 15.7623 4.38674 16.2372 4.67963 16.53C4.97252 16.8229 5.4474 16.8229 5.74029 16.53L4.67963 15.4694ZM9.57224 16.1248L7.46982 18.2272L8.53048 19.2879L10.6329 17.1855L9.57224 16.1248ZM6.79653 13.3525L4.67963 15.4694L5.74029 16.53L7.85719 14.4131L6.79653 13.3525Z">
                                            </path>
                                            <path opacity="0.5"
                                                d="M12.3732 12.645C12.6667 12.3527 12.6677 11.8779 12.3754 11.5844C12.0831 11.2908 11.6083 11.2898 11.3148 11.5821L12.3732 12.645ZM10.581 14.4297L12.3732 12.645L11.3148 11.5821L9.52255 13.3668L10.581 14.4297ZM17.2888 8.0128C16.9216 8.37849 16.3248 8.37849 15.9576 8.01279L14.8992 9.07569C15.8517 10.0242 17.3947 10.0242 18.3472 9.07569L17.2888 8.0128ZM15.9576 8.01279C15.5921 7.64885 15.5921 7.06009 15.9576 6.69614L14.8992 5.63325C13.9449 6.58349 13.9449 8.12545 14.8992 9.07569L15.9576 8.01279ZM15.9576 6.69614C16.3248 6.33045 16.9216 6.33045 17.2888 6.69614L18.3472 5.63325C17.3947 4.68475 15.8517 4.68475 14.8992 5.63325L15.9576 6.69614ZM17.2888 6.69614C17.6543 7.06009 17.6543 7.64885 17.2888 8.0128L18.3472 9.07569C19.3015 8.12545 19.3015 6.58349 18.3472 5.63325L17.2888 6.69614Z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="w-ful">
                                        <p class="text-primary text-sm">Reúna 3 imagens iguais e conquiste seu
                                            prêmio!</p>
                                        <p class="text-sm text-[#a1a1a1]">O valor correspondente será creditado
                                            automaticamente na sua conta.</p>
                                        <p class="text-sm text-[#a1a1a1]">Se preferir receber o produto físico, basta
                                            entrar em contato com o nosso suporte.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <PremiosDinamicos :gameId="gameId" />
                </div>
            </div>
        </div>
    </GameLayout>
</template>

<script>
import { initFlowbite, Tabs, Modal } from 'flowbite';
import { RouterLink, useRoute, useRouter } from "vue-router";
import { useAuthStore } from "@/Stores/Auth.js";
import { component } from 'vue-fullscreen';
import LoadingComponent from "@/Components/UI/LoadingComponent.vue";
import GameLayout from "@/Layouts/GameLayout.vue";
import HttpApi from "@/Services/HttpApi.js";
import PremiosDinamicos from './Components/PremiosDinamicos.vue';
import { useToast } from "vue-toastification";

import {
    defineComponent,
    toRefs,
    reactive,
} from 'vue';
import CompradoresAoVivo from '../Home/Components/CompradoresAoVivo.vue';

export default {
    props: [],
    components: {
        GameLayout,
        CompradoresAoVivo,
        LoadingComponent,
        RouterLink,
        PremiosDinamicos,
        component
    },
    data() {
        return {
            isLoading: true,
            game: null,
            modeMovie: false,
            gameUrl: null,
            token: null,
            gameId: null,
            tabs: null,
            undermaintenance: false,
            canvas: null,
            ctx: null,
            isDrawing: false,
            scratchedPercentage: 0,
            scratchCards: [],
            gameItems: Array(9).fill({ 
                name: 'Carregando...',
                image: 'https://via.placeholder.com/100x100/374151/ffffff?text=?',
                value: 'R$ 0,00'
            }), 
            isGameComplete: false,
            hasWon: false,
            winningPrize: null,
            potentialWinningPrize: null, 
            autoRevealEnabled: false, 
            autoPlayEnabled: false,
            autoPlayInterval: null,
            hasPurchased: false, 
            userBalance: 0, 
            demoGameEnabled: false,
        }
    },
    setup() {
        const router = useRouter();
        const state = reactive({
            fullscreen: false,
            pageOnly: false,
        })
        function togglefullscreen() {
            console.log("CLICOU");
            state.fullscreen = !state.fullscreen
        }

        return {
            ...toRefs(state),
            togglefullscreen,
            router
        }
    },
    computed: {
        userData() {
            const authStore = useAuthStore();
            return authStore.user;
        },
        isAuthenticated() {
            const authStore = useAuthStore();
            return authStore.isAuth;
        },
    },
    mounted() {

        const userAgent = navigator.userAgent.toLowerCase();
        const isSafari = userAgent.includes('safari') && !userAgent.includes('chrome');
        const isSamsungInternet = userAgent.includes('samsung') && userAgent.includes('safari') && !userAgent.includes('chrome');
        const isIOS = userAgent.includes('iphone') || userAgent.includes('ipad');

        if (isSafari || isSamsungInternet || isIOS) {
            this.showButton = true;
        }

        this.$nextTick(() => {
            this.initScratchCard();
        });
    },
    methods: {
        getToast() {
            if (!this._toastInstance) {
                this._toastInstance = useToast();
            }
            return this._toastInstance;
        },

        openRegisterModal() {
            let modalRegistro = new Modal(document.getElementById('modalElRegister'));
            modalRegistro.show();
        },
        initScratchCard() {
            if (!this.isAuthenticated) return;
            
            // Usar um timeout para garantir que o DOM esteja completamente carregado
            setTimeout(() => {
                this.canvas = document.querySelector('canvas');
                if (!this.canvas) {
                    console.error('Canvas não encontrado, tentando novamente...');
                    // Se não encontrar o canvas, tentar novamente após um pequeno delay
                    setTimeout(() => this.initScratchCard(), 500);
                    return;
                }
                
                try {
                    // Usar willReadFrequently para otimizar a performance
                    this.ctx = this.canvas.getContext('2d', { willReadFrequently: true });
                    
                    if (!this.ctx) {
                        console.error('Não foi possível obter o contexto do canvas');
                        return;
                    }
                    
                    console.log('Canvas inicializado com sucesso');
                    this.addEventListeners();
                    
                    // Se já comprou, configurar o canvas imediatamente
                    if (this.hasPurchased && !this.isGameComplete) {
                        this.setupCanvas();
                    }
                } catch (err) {
                    console.error('Erro ao inicializar o canvas:', err);
                }
            }, 200);
        },

        getRandomPositions(count) {
            const positions = [];
            while (positions.length < count) {
                const pos = Math.floor(Math.random() * 9);
                if (!positions.includes(pos)) {
                    positions.push(pos);
                }
            }
            return positions;
        },

        demoMode(){
            this.toggleDemoGame();
            let mensagem = this.demoGameEnabled ? 'Modo de demonstração ativado. Você pode raspar sem gastar saldo.' : 'Modo de demonstração desativado.';
            this.getToast().info(mensagem, {
                timeout: 5000,
                position: 'top-right',
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
                draggablePercent: 0.6,
                showCloseButtonOnHover: true,
                hideProgressBar: true,
                icon: true,
                transition: 'Vue-Toastification__fade',
            });
        },

        setupCanvas() {
            if (!this.hasPurchased) return;
            
            const parentElement = this.canvas.parentElement;
            const rect = parentElement.getBoundingClientRect();
            
            this.canvas.width = rect.width;
            this.canvas.height = rect.height;
            
            this.canvas.style.width = rect.width + 'px';
            this.canvas.style.height = rect.height + 'px';
            
            // Desenhar um fundo imediato enquanto carrega
            this.ctx.fillStyle = '#1f2937';
            this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
            
            // Texto de carregamento
            this.ctx.fillStyle = '#CCCCCC';
            this.ctx.font = 'bold 20px Arial';
            this.ctx.textAlign = 'center';
            this.ctx.fillText('RASPE AQUI', this.canvas.width / 2, this.canvas.height / 2);
            
            // Configurar um timeout para caso a imagem demore muito para carregar
            const imageTimeout = setTimeout(() => {
                console.warn('A imagem da raspadinha demorou muito para carregar, usando fallback.');
                this.drawFallbackCover();
            }, 3000); // 3 segundos de timeout

            const scratchImage = new Image();
            scratchImage.crossOrigin = 'anonymous';
            
            scratchImage.onload = () => {
                clearTimeout(imageTimeout);
                
                // Verificar se a imagem tem dimensões válidas
                if (scratchImage.width <= 0 || scratchImage.height <= 0) {
                    console.error('Imagem carregada com dimensões inválidas');
                    this.drawFallbackCover();
                    return;
                }
                
                try {
                    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                    
                    this.ctx.fillStyle = '#1f2937';
                    this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
                    
                    const canvasRatio = this.canvas.width / this.canvas.height;
                    const imageRatio = scratchImage.width / scratchImage.height;
                    
                    let drawWidth, drawHeight, drawX, drawY;
                    
                    if (canvasRatio > imageRatio) {
                        drawWidth = this.canvas.width;
                        drawHeight = this.canvas.width / imageRatio;
                        drawX = 0;
                        drawY = (this.canvas.height - drawHeight) / 2;
                    } else {
                        drawHeight = this.canvas.height;
                        drawWidth = this.canvas.height * imageRatio;
                        drawX = (this.canvas.width - drawWidth) / 2;
                        drawY = 0;
                    }
                    
                    // Desenhar a imagem no canvas
                    this.ctx.drawImage(scratchImage, drawX, drawY, drawWidth, drawHeight);
                    
                    // Verificar se a imagem foi realmente desenhada
                    const imageData = this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height);
                    let hasContent = false;
                    
                    // Verificar alguns pixels para ver se não estão completamente transparentes
                    for (let i = 3; i < Math.min(imageData.data.length, 10000); i += 40) {
                        if (imageData.data[i] > 0) {
                            hasContent = true;
                            break;
                        }
                    }
                    
                    if (!hasContent) {
                        console.error('Imagem carregada mas não está visível no canvas');
                        this.drawFallbackCover();
                    }
                } catch (err) {
                    console.error('Erro ao desenhar a imagem:', err);
                    this.drawFallbackCover();
                }
            };
            
            scratchImage.onerror = () => {
                clearTimeout(imageTimeout);
                console.error('Erro ao carregar a imagem de raspagem.');
                this.drawFallbackCover();
            };
            
            // Tentar carregar imagem com cache buster para evitar cache
            const imagePath = window.location.origin + '/assets/images/banners/cover-scratch.png';
            console.log('Setting up canvas with image:', imagePath);
            scratchImage.src = imagePath + '?t=' + new Date().getTime();
        },
        
        drawFallbackCover() {
            // Criar uma imagem de raspadinha visual caso a imagem original falhe
            try {
                // Limpar o canvas e criar um fundo base
                this.ctx.fillStyle = '#1f2937';
                this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
                
                // Adicionar um gradiente para dar profundidade
                const gradient = this.ctx.createLinearGradient(0, 0, this.canvas.width, this.canvas.height);
                gradient.addColorStop(0, '#2d3748');
                gradient.addColorStop(1, '#1a202c');
                this.ctx.fillStyle = gradient;
                this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
                
                // Desenhar texto principal
                this.ctx.fillStyle = '#f7fafc';
                this.ctx.font = 'bold 24px Arial';
                this.ctx.textAlign = 'center';
                this.ctx.fillText('RASPE AQUI', this.canvas.width / 2, this.canvas.height / 2 - 15);
                
                this.ctx.fillStyle = '#a0aec0';
                this.ctx.font = '16px Arial';
                this.ctx.fillText('Revele os prêmios!', this.canvas.width / 2, this.canvas.height / 2 + 15);
                
                // Desenhar padrão de moedas
                for (let i = 0; i < 10; i++) {
                    const x = Math.random() * this.canvas.width;
                    const y = Math.random() * this.canvas.height;
                    const size = 10 + Math.random() * 10;
                    
                    this.ctx.beginPath();
                    this.ctx.arc(x, y, size, 0, Math.PI * 2);
                    this.ctx.fillStyle = 'rgba(251, 191, 36, 0.4)'; // Cor de moeda com transparência
                    this.ctx.fill();
                    this.ctx.strokeStyle = 'rgba(251, 191, 36, 0.8)';
                    this.ctx.lineWidth = 1;
                    this.ctx.stroke();
                }
                
                // Desenhar bordas estilizadas
                this.ctx.strokeStyle = '#4a5568';
                this.ctx.lineWidth = 8;
                this.ctx.strokeRect(10, 10, this.canvas.width - 20, this.canvas.height - 20);
            } catch (err) {
                console.error('Erro ao desenhar fallback:', err);
                // Último recurso - texto básico
                this.ctx.fillStyle = '#1f2937';
                this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
                this.ctx.fillStyle = '#ffffff';
                this.ctx.font = 'bold 20px Arial';
                this.ctx.textAlign = 'center';
                this.ctx.fillText('RASPE AQUI', this.canvas.width / 2, this.canvas.height / 2);
            }
        },

        addEventListeners() {
            this.canvas.addEventListener('mousedown', this.startScratch);
            this.canvas.addEventListener('mousemove', this.scratch);
            this.canvas.addEventListener('mouseup', this.stopScratch);
            this.canvas.addEventListener('mouseleave', this.stopScratch);

            this.canvas.addEventListener('touchstart', this.startScratch);
            this.canvas.addEventListener('touchmove', this.scratch);
            this.canvas.addEventListener('touchend', this.stopScratch);
            
            document.addEventListener('mousemove', this.globalMouseMove);
            document.addEventListener('mouseup', this.globalMouseUp);
            document.addEventListener('touchmove', this.globalTouchMove);
            document.addEventListener('touchend', this.globalTouchEnd);
            document.addEventListener('keydown', this.globalKeyDown);

            window.addEventListener('resize', this.handleResize);
        },

        handleResize() {
            if (this.canvas && this.ctx && !this.isGameComplete) {
                this.setupCanvas();
            }
        },

        globalKeyDown(e) {
            e.preventDefault();
            const keyName = e.key;

            if (keyName === "Control") {
                return;
            }

            if (e.ctrlKey) {
                switch (keyName) {
                    case 'b':
                        this.toggleAutoReveal();
                        break;
                    case 'r':
                        this.buyNewCard();
                        break;
                    case 'd':
                        this.demoMode();
                        break;
                    default:
                        console.warn(`Tecla de atalho não reconhecida: ${keyName}`);
                }
            }
        },

        globalMouseMove(e) {
            if (!this.isDrawing) return;
            
            const rect = this.canvas.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            if (x >= 0 && y >= 0 && x <= this.canvas.width && y <= this.canvas.height) {
                this.ctx.globalCompositeOperation = 'destination-out';
                this.ctx.beginPath();
                this.ctx.arc(x, y, 40, 0, 2 * Math.PI);
                this.ctx.fill();

                this.checkScratchedPercentage();
            }
        },

        globalMouseUp(e) {
            if (this.isDrawing) {
                this.stopScratch();
            }
        },

        globalTouchMove(e) {
            if (!this.isDrawing) return;
            
            e.preventDefault();
            const rect = this.canvas.getBoundingClientRect();
            const x = e.touches[0].clientX - rect.left;
            const y = e.touches[0].clientY - rect.top;
            
            if (x >= 0 && y >= 0 && x <= this.canvas.width && y <= this.canvas.height) {
                this.ctx.globalCompositeOperation = 'destination-out';
                this.ctx.beginPath();
                this.ctx.arc(x, y, 40, 0, 2 * Math.PI);
                this.ctx.fill();

                this.checkScratchedPercentage();
            }
        },

        globalTouchEnd(e) {
            if (this.isDrawing) {
                this.stopScratch();
            }
        },

        startScratch(e) {
            if (this.isGameComplete) return;
            
            if (!this.hasPurchased) {
                this.getToast().warning('🎰 Você precisa comprar uma cartela primeiro!', {
                    position: 'top-right',
                    timeout: 4000
                });
                return;
            }
            
            if (this.autoRevealEnabled) {
                this.revealAll();
                return;
            }
            
            this.isDrawing = true;
            this.scratch(e);
        },

        scratch(e) {
            if (!this.isDrawing) return;

            e.preventDefault();
            const rect = this.canvas.getBoundingClientRect();
            let x, y;

            if (e.type.startsWith('touch')) {
                x = e.touches[0].clientX - rect.left;
                y = e.touches[0].clientY - rect.top;
            } else {
                x = e.clientX - rect.left;
                y = e.clientY - rect.top;
            }

            this.ctx.globalCompositeOperation = 'destination-out';
            this.ctx.beginPath();
            this.ctx.arc(x, y, 40, 0, 2 * Math.PI);
            this.ctx.fill();

            this.checkScratchedPercentage();
        },

        stopScratch() {
            this.isDrawing = false;
        },

        checkScratchedPercentage() {
            const imageData = this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height);
            const pixels = imageData.data;
            let transparentPixels = 0;
            
            for (let i = 3; i < pixels.length; i += 4) {
                if (pixels[i] === 0) {
                    transparentPixels++;
                }
            }
            
            this.scratchedPercentage = (transparentPixels / (pixels.length / 4)) * 100;
            
            if (this.scratchedPercentage > 70 && !this.isGameComplete) {
                this.revealAll();
            }
        },

        revealAll() {
            this.isGameComplete = true;
            this.canvas.style.opacity = '0';
            
            setTimeout(() => {
                this.checkForWin();
            }, 500);
        },

        toggleAutoReveal() {
            this.autoRevealEnabled = !this.autoRevealEnabled;
        },

        toggleAutoPlay() {
            this.autoPlayEnabled = !this.autoPlayEnabled;
            
            if (this.autoPlayEnabled) {
                // Ativar também o auto reveal quando a rodada automática for ativada
                this.autoRevealEnabled = true;
                
                this.getToast().success('🤖 Rodada automática ativada!', {
                    position: 'top-right',
                    timeout: 3000,
                    icon: '🔄'
                });
                
                // Se não há jogo em andamento, começar um novo automaticamente
                if (!this.hasPurchased && this.isGameComplete) {
                    this.startAutoPlay();
                }
            } else {
                // Parar a rodada automática
                if (this.autoPlayInterval) {
                    clearTimeout(this.autoPlayInterval);
                    this.autoPlayInterval = null;
                }
                
                this.getToast().info('⏹️ Rodada automática desativada', {
                    position: 'top-right',
                    timeout: 3000,
                    icon: '⏸️'
                });
            }
        },

        startAutoPlay() {
            if (!this.autoPlayEnabled) return;
            
            // Verificar se tem saldo suficiente
            if (this.userBalance < this.game?.valor && !this.demoGameEnabled) {
                this.autoPlayEnabled = false;
                this.getToast().error('❌ Saldo insuficiente para continuar a rodada automática', {
                    position: 'top-right',
                    timeout: 5000,
                    icon: '💰'
                });
                return;
            }
            
            // Comprar nova cartela automaticamente
            this.buyNewCard();
        },

        toggleDemoGame() {
            if(!this.hasPurchased){
                this.demoGameEnabled = !this.demoGameEnabled;
            }
        },

        checkForWin() {
            const productCount = {};
            this.gameItems.forEach(item => {
                productCount[item.name] = (productCount[item.name] || 0) + 1;
            });

            for (const productName in productCount) {
                if (productCount[productName] >= 3) {
                    this.hasWon = true;
                    this.gameItems.forEach(item => {
                        if (item.name === productName) {
                            item.isWinning = true;
                        }
                    });
                    // Encontrar o produto completo para usar no toast
                    this.winningPrize = this.gameItems.find(item => item.name === productName);
                    this.showWinToast();
                    return;
                }
            }

            this.showLoseToast();
        },

        showWinToast(response = null) {
            if (response && response.data && response.data.money_deposited) {
                this.getToast().success(`💰 ${response.message}`, {
                    position: 'top-right',
                    timeout: 5000,
                    icon: '💰'
                });
            } else {
                let message = `🎉 Parabéns! Você ganhou: ${this.winningPrize.name}`;
                
                if (this.winningPrize.type === 'money') {
                    message += ` - Valor: ${this.winningPrize.value}`;
                } else {
                    message += ` (${this.winningPrize.value})`;
                }
                
                this.getToast().success(message, {
                    position: 'top-right',
                    timeout: 8000,
                    icon: '🏆',
                    className: 'toast-success-win'
                });
                
                if (this.winningPrize.type === 'product') {
                    setTimeout(() => {
                        this.getToast().info('📞 Entre em contato com o suporte para retirar seu prêmio!', {
                            position: 'top-right',
                            timeout: 6000
                        });
                    }, 1000);
                }
            }
        },

        showLoseToast() {
            this.getToast().info('😔 Que pena! Tente novamente na próxima!', {
                position: 'top-right',
                timeout: 4000,
                icon: '🎯'
            });
        },

        resetGame() {
            this.isGameComplete = false;
            this.hasWon = false;
            this.winningPrize = null;
            this.potentialWinningPrize = null;
            this.hasPurchased = false; 
            this.scratchedPercentage = 0;
            this.isDrawing = false; 
            this.gameItems = []; 
            if (this.canvas) {
                this.canvas.style.opacity = '1';
            }
            
            // Parar rodada automática se estiver ativa
            if (this.autoPlayInterval) {
                clearTimeout(this.autoPlayInterval);
                this.autoPlayInterval = null;
            }
        },

        removeEventListeners() {
            document.removeEventListener('mousemove', this.globalMouseMove);
            document.removeEventListener('mouseup', this.globalMouseUp);
            document.removeEventListener('touchmove', this.globalTouchMove);
            document.removeEventListener('touchend', this.globalTouchEnd);
            window.removeEventListener('resize', this.handleResize);
        },

        buyNewCard() {
            if (this.hasPurchased && !this.isGameComplete) return;
            
            if (this.isGameComplete) {
                this.resetGame();
                
                this.$nextTick(() => {
                    this.purchaseScratchCard();
                });
                
                return;
            }
            
            if (!this.hasPurchased) {
                this.purchaseScratchCard();
            }
        },

        async purchaseScratchCard() {
            try {
                this.isLoading = true;

                const response = await HttpApi.post('scratch-card/buy', {
                    amount: this.game.valor, 
                    game_id: this.gameId,
                    is_demo: this.demoGameEnabled
                });

                if (response.data.success) {
                    this.hasPurchased = true;
                    this.gameItems = response.data.data.game_items;
                    this.gameItems.order_id = response.data.data.order_id;

                    // Atualizar saldo do usuário
                    if (response.data.data.new_balance !== undefined) {
                        this.userBalance = response.data.data.new_balance;
                    }
                    
                    // Configurar o canvas após a compra
                    this.$nextTick(() => {
                        // Verificar se o canvas está pronto
                        if (!this.canvas || !this.ctx) {
                            console.log('Canvas não está pronto, reinicializando...');
                            this.initScratchCard();
                            setTimeout(() => this.setupCanvas(), 300);
                        } else {
                            this.setupCanvas();
                        }
                    });
                    
                    // Mostrar toast de compra realizada
                    this.getToast().success(response.data.message, {
                        position: 'top-right',
                        timeout: 4000,
                        icon: '🎫'
                    });
                } else {
                    throw new Error(response.data.message);
                }
            } catch (error) {
                console.error('Erro ao comprar cartela:', error);
                
                let errorMessage = 'Erro ao comprar cartela';
                if (error.response && error.response.data) {
                    errorMessage = error.response.data.message || errorMessage;
                } else if (error.message) {
                    errorMessage = error.message;
                }
                
                // Se for erro de saldo insuficiente, parar rodada automática
                if (errorMessage.toLowerCase().includes('saldo') || errorMessage.toLowerCase().includes('insuficiente')) {
                    this.autoPlayEnabled = false;
                    if (this.autoPlayInterval) {
                        clearTimeout(this.autoPlayInterval);
                        this.autoPlayInterval = null;
                    }
                }
                
                this.getToast().error(errorMessage, {
                    position: 'top-right',
                    timeout: 6000
                });
            } finally {
                this.isLoading = false;
            }
        },

        async gamesNotFinalized() {
            try {
                const response = await HttpApi.get('scratch-card/not-finalized?game_id=' + this.gameId);
                if (response.data.success) {
                    if (response.data.data.order_id === null) {
                        return;
                    }

                    this.gameItems = response.data.data.game_items;
                    this.hasPurchased = true;
                    this.gameItems.order_id = response.data.data.order_id;

                    // Atualizar saldo do usuário
                    if (response.data.data.new_balance !== undefined) {
                        this.userBalance = response.data.data.new_balance;
                    }

                    // Configurar o canvas após a compra
                    this.$nextTick(() => {
                        this.setupCanvas();
                    });
                }
            } catch (error) {
                console.error('Erro ao buscar jogos não finalizados:', error);
            }
        },

        async checkForWin() {
            // Contar ocorrências de cada produto (baseado no nome)
            const productCount = {};
            this.gameItems.forEach(item => {
                productCount[item.name] = (productCount[item.name] || 0) + 1;
            });

            // Verificar se há 3 ou mais produtos iguais
            let hasWon = false;
            let winningPrize = null;

            for (const productName in productCount) {
                if (productCount[productName] >= 3) {
                    hasWon = true;
                    // Destacar os produtos vencedores
                    this.gameItems.forEach(item => {
                        if (item.name === productName) {
                            item.isWinning = true;
                        }
                    });
                    // Encontrar o produto completo para usar no toast
                    winningPrize = this.gameItems.find(item => item.name === productName);
                    break;
                }
            }

            this.hasWon = hasWon;
            this.winningPrize = winningPrize;

            // Enviar resultado para o backend
            let apiResponse = null;
            try {
                apiResponse = await HttpApi.post('scratch-card/result', {
                    game_items: this.gameItems,
                    has_won: hasWon,
                    winning_prize: winningPrize,
                    order_id: this.gameItems.order_id
                });
                
                // Se houve depósito de dinheiro, atualizar saldo local
                if (apiResponse.data.success && apiResponse.data.data.money_deposited) {
                    this.userBalance += apiResponse.data.data.deposit_amount;
                }
            } catch (error) {
                console.error('Erro ao processar resultado:', error);
            }

            this.isGameComplete = true;
            this.hasPurchased = false;

            // Mostrar resultado na interface
            if (hasWon) {
                this.showWinToast(apiResponse);
            } else {
                this.showLoseToast();
            }
            
            // Se a rodada automática estiver ativa, começar uma nova rodada após um delay
            if (this.autoPlayEnabled) {
                this.autoPlayInterval = setTimeout(() => {
                    this.startAutoPlay();
                }, 2000); // Aguardar 2 segundos antes da próxima rodada
            }
        },

        async fetchUserBalance() {
            try {
                const response = await HttpApi.get('scratch-card/balance');
                if (response.data.success) {
                    this.userBalance = response.data.data.total_balance;
                }
            } catch (error) {
                console.error('Erro ao buscar saldo:', error);
            }
        },

        loadingTab: function () {
            const tabsElement = document.getElementById('tabs-info');
            if (tabsElement) {
                const tabElements = [
                    {
                        id: 'default',
                        triggerEl: document.querySelector('#default-tab'),
                        targetEl: document.querySelector('#default-panel'),
                    },
                    {
                        id: 'descriptions',
                        triggerEl: document.querySelector('#description-tab'),
                        targetEl: document.querySelector('#description-panel'),
                    },
                    {
                        id: 'reviews',
                        triggerEl: document.querySelector('#reviews-tab'),
                        targetEl: document.querySelector('#reviews-panel'),
                    },
                ];

                const options = {
                    defaultTabId: 'default',
                    activeClasses: 'text-green-600 hover:text-green-600 dark:text-green-500 dark:hover:text-green-400 border-green-600 dark:border-green-500',
                    inactiveClasses: 'text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300',
                    onShow: () => {

                    },
                };

                const instanceOptions = {
                    id: 'default',
                    override: true
                };

                /*
                * tabElements: array of tab objects
                * options: optional
                * instanceOptions: optional
                */
                this.tabs = new Tabs(tabsElement, tabElements, options, instanceOptions);
            }
        },
        openModal(gameUrl) {
            window.open(gameUrl);
        },

        getGame: async function () {
            const _this = this;

            return await HttpApi.get('games/single/' + _this.gameId)
                .then(async response => {
                    /*if (response.data?.action === 'deposit') {
                        _this.$nextTick(() => {
                            _this.router.push({ name: 'profileDeposit' });
                        });
                    }*/

                    const game = response.data;
                    _this.game = game;

                    // if(game.distribution == 'evergame') {
                    //     window.open(response.data.gameUrl)
                    // }

                    _this.gameUrl = response.data.gameUrl;
                    _this.token = response.data.token;
                    _this.isLoading = false;

                    _this.$nextTick(() => {
                        _this.loadingTab();
                    });
                })
                .catch(error => {

                    _this.isLoading = false;
                    _this.undermaintenance = true;
                    Object.entries(JSON.parse(error.request.responseText)).forEach(([key, value]) => {

                    });
                });
        },
        toggleFavorite: function () {
            const _this = this;
            return HttpApi.post('games/favorite/' + _this.game.id, {})
                .then(response => {
                    _this.getGame();
                    _this.isLoading = false;
                })
                .catch(error => {
                    _this.isLoading = false;
                });
        },
        toggleLike: async function () {
            const _this = this;
            return await HttpApi.post('games/like/' + _this.game.id, {})
                .then(async response => {
                    await _this.getGame();
                    _this.isLoading = false;
                })
                .catch(error => {
                    _this.isLoading = false;
                });
        }
    },
    async created() {
        const route = useRoute();
            this.gameId = route.params.id;

            await this.getGame();
            
            // Buscar saldo inicial do usuário
            await this.fetchUserBalance();

            await this.gamesNotFinalized();
            
            // Inicializar a raspadinha após carregar o jogo
            this.$nextTick(() => {
                this.initScratchCard();
            });
    },
    beforeUnmount() {
        // Limpar event listeners globais
        this.removeEventListeners();
        
        // Limpar intervalo da rodada automática
        if (this.autoPlayInterval) {
            clearTimeout(this.autoPlayInterval);
            this.autoPlayInterval = null;
        }
        
        // Desativar rodada automática
        this.autoPlayEnabled = false;
    },
    watch: {

    },
};
</script>

<style>
.game-screen {
    margin-top: 30px;
    width: 100%;
    min-height: 650px;
}

.game-screen .game-full {
    width: 100%;
    min-height: 650px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

.game-footer {
    border-bottom-left-radius: 8px;
    border-bottom-right-radius: 8px;
}

/* Estilos para a raspadinha */
canvas {
    cursor: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='50' height='50' viewport='0 0 50 50' style='fill:gold;font-size:30px;'><circle cx='25' cy='25' r='20' fill='rgba(255,215,0,0.3)' stroke='gold' stroke-width='2'/><text x='25' y='32' text-anchor='middle' style='font-size:20px;'>🪙</text></svg>") 25 25, auto;
    width: 100% !important;
    height: 100% !important;
    display: block;
}

.scratch-item {
    transition: all 0.3s ease;
}

.scratch-item.revealed {
    transform: scale(1.1);
    box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
}

.button-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Estilos para os produtos da raspadinha */
.scratch-product {
    background: linear-gradient(135deg, #1f2937, #374151);
    border: 1px solid #4b5563;
}

.scratch-product img {
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.scratch-product span {
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.8);
}

/* Animação para rodada automática */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 2s linear infinite;
}
</style>
