<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Post') }}
        </h2>
    </x-slot>

	<div class=" flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="w-11/12 lg:w-full md:w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden rounded-lg mb-12">
         <x-jet-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('post.save') }}" enctype="multipart/form-data">
            @csrf

            <div>
                <x-jet-input id="title" class="block mt-1 mb-2 w-full" value="{{ $post->id }}" type="hidden" name="id" wire:model.lazy="title" />
            </div>

            <div>
                <x-jet-label for="title" value="{{ __('Title') }}" />
                <x-jet-input id="title" class="block mt-1 mb-2 w-full" value="{{ $post->title }}" type="text" name="title" wire:model.lazy="title" />
            </div>

            <div>
                <x-jet-label for="location" value="{{ __('Location') }}" />
                <x-jet-input id="location" class="block mt-1 w-full" value="{{ $post->location }}" type="text" name="location" wire:model.lazy="location" />
            </div>

            <div class="mt-4">
                <x-jet-label for="body" value="{{ __('Description') }}" />
               <textarea rows="5" name="body" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow" wire:model.lazy="body">{{ $post->body }}</textarea>
            </div>

            <div class="mt-4">
            <select class="js-example-basic-multiple" name="tags[]" multiple="multiple" style="width:100%;">
                @foreach($users as $user)
                    <option value="{{$user->username}}">{{$user->email}}</option>
                @endforeach
            </select>
            </div>

         <div
		    x-data="{ isUploading: false, progress: 0 }"
		    x-on:livewire-upload-start="isUploading = true"
		    x-on:livewire-upload-finish="isUploading = false"
		    x-on:livewire-upload-error="isUploading = false"
			x-on:livewire-upload-progress="progress = $event.detail.progress"
			>

            <div class="mt-4">
                <x-jet-label for="body" value="{{ __('Media') }}" />
               <input type="file" name="file" wire:model="file">
            </div>



            <div wire:loading class="my-3" wire:target="file">Uploading...</div>

	        <!-- Progress Bar -->
		    <div x-show="isUploading" class="my-2">
		        <progress max="100" x-bind:value="progress"></progress>
		    </div>
            @if($post->postImages->count() !== 0)
            <div>
                @foreach($post->postImages as $image)
                    <div style="position: relative; display: inline-block;">
                        <img src="{{'storage/'.$image->path}}" class="image">
                        <a href="/delete/image/{{$image->id}}">
                            <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; background-color: rgba(0, 0, 0, 0.7); padding: 5px 10px; border-radius: 5px; transition: opacity 0.3s; cursor: pointer;">Remove</span>
                        </a>
                    </div>
                @endforeach
            </div>
            @else
            <div>
                No images posted
            </div>
            @endif
		</div>

            <div class="flex items-center justify-end mt-4">

                <x-jet-button class="ml-4">
                    {{ __('Save Edit') }}
                </x-jet-button>
            </div>

        </form>

        @section('scripts')
        <script>
            $(document).ready(function() {
                $('.js-example-basic-multiple').select2();
            });
        </script>
        @endsection
    </div>

</div>

</x-app-layout>
