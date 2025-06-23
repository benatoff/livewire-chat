<div
    wire:loading.flex
    wire:target="runChatToolLoop"
    wire:key="loading-{{ count($this->messages) }}"
    class="relative mx-auto hidden w-full max-w-4xl flex-1"
>
    <div class="flex w-full flex-row justify-start">
        <div
            class="prose prose-sm max-h-fit max-w-fit min-w-24 space-y-2 rounded-xl border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-600 dark:bg-zinc-700"
            x-data="streamedMarkdown()"
        >
            <flux:heading>AI</flux:heading>

            <!-- Thinking indicator -->
            <div x-show="isCurrentlyThinking()" class="flex items-center gap-2 text-zinc-500 dark:text-zinc-400">
                <div class="flex space-x-1">
                    <div class="h-1 w-1 animate-bounce rounded-full bg-zinc-400 [animation-delay:-0.3s]"></div>
                    <div class="h-1 w-1 animate-bounce rounded-full bg-zinc-400 [animation-delay:-0.15s]"></div>
                    <div class="h-1 w-1 animate-bounce rounded-full bg-zinc-400"></div>
                </div>
                <span class="text-sm">Thinking...</span>
            </div>

            <!-- Tool usage indicator -->
            <div x-show="isCurrentlyUsingTools()" class="flex items-center gap-2 text-blue-500 dark:text-blue-400">
                <flux:icon.wrench-screwdriver class="h-4 w-4 animate-spin" />
                <span class="text-sm">Using tools...</span>
            </div>

            <!-- Thinking preview (collapsible) -->
            <div x-show="hasThinking()" class="border-t border-zinc-200 pt-2 dark:border-zinc-600">
                <button
                    @click="toggleThinking()"
                    class="flex items-center gap-1 text-sm text-zinc-600 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200"
                >
                    <flux:icon.chevron-right x-show="!showThinking" class="h-3 w-3" />
                    <flux:icon.chevron-down x-show="showThinking" class="h-3 w-3" />
                    Thinking process
                </button>
                <div x-show="showThinking" x-collapse class="mt-2">
                    <div class="rounded bg-zinc-100 p-2 text-xs dark:bg-zinc-800">
                        <article
                            wire:ignore
                            class="prose prose-zinc prose-xs prose-p:m-0 prose-code:font-mono prose-pre:text-xs dark:prose-invert max-w-none break-words"
                            x-html="thinkingHtml"
                        ></article>
                    </div>
                </div>
            </div>

            <!-- Tool calls display -->
            <div x-show="hasToolCalls()" class="border-t border-zinc-200 pt-2 dark:border-zinc-600">
                <template x-for="toolCall in streamData.toolCalls" :key="toolCall.name + Date.now()">
                    <div class="mt-1 flex items-center gap-2 text-xs">
                        <flux:icon.wrench-screwdriver class="h-3 w-3 text-blue-500" />
                        <span x-text="toolCall.name" class="font-mono text-blue-600 dark:text-blue-400"></span>
                    </div>
                </template>
            </div>

            <!-- Main content -->
            <flux:text>
                <span x-ref="raw" class="hidden" wire:stream="streamed-message" wire:replace></span>
                <article
                    wire:ignore
                    class="prose prose-zinc prose-sm prose-p:m-0 prose-code:font-mono prose-pre:border prose-pre:border-zinc-200 prose-pre:dark:border-zinc-600 prose-pre:rounded-md prose-pre:p-4 prose-pre:mb-1 prose-pre:bg-zinc-100 prose-pre:dark:bg-zinc-800 prose-pre:text-zinc-900 prose-pre:dark:text-zinc-100 dark:prose-invert max-w-none min-w-0 overflow-hidden break-words"
                    x-html="html"
                    x-show="html.length > 0"
                ></article>
            </flux:text>
        </div>
    </div>
</div>
