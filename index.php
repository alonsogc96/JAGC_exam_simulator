<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Exam Simulator</title>
</head>
<body>
    <section class="bg-white">
        <div class="lg:grid lg:min-h-screen lg:grid-cols-12">
            <!-- Side Image Section -->
            <section class="relative flex h-32 items-end bg-gray-900 lg:col-span-5 lg:h-full xl:col-span-6">
                <img
                    alt=""
                    src="https://miro.medium.com/v2/resize:fit:1400/0*OVVk65gLK95z3AFU.jpg"
                    class="absolute inset-0 h-full w-full object-cover opacity-80"
                />
                <div class="hidden lg:relative lg:block lg:p-12">
                    <h2 class="mt-6 text-2xl font-bold text-white sm:text-3xl md:text-4xl">
                        &nbsp;Welcome to JAGC Simulator
                    </h2>
                    <p class="mt-4 leading-relaxed text-white/90">
                        &nbsp;-------------------------------------------
                    </p>
                </div>
            </section>

            <!-- Main Content Section -->
            <main class="flex items-center justify-center px-8 py-8 sm:px-12 lg:col-span-7 lg:px-16 lg:py-12 xl:col-span-6">
                <div class="max-w-xl lg:max-w-3xl" >
                    <div class="relative -mt-16 block lg:hidden" style="padding: 40px 0 0 0;">
                        <h1 class="mt-2 text-2xl font-bold text-gray-900 sm:text-3xl md:text-4xl">
                            Welcome to JAGC Simulator
                        </h1>
                        <p class="mt-4 leading-relaxed text-gray-500">
                            -------------------------------------------
                        </p>
                    </div>
                    <div class="flex flex-col gap-4" style="padding: 40px 0 0 0;">
                        <a href="create_exam.php" class="w-full">
                            <button class="w-full rounded-md border border-blue-600 bg-blue-600 px-8 py-4 text-lg font-medium text-white transition hover:bg-transparent hover:text-blue-600 focus:outline-none focus:ring active:text-blue-500">
                                Create a Exam
                            </button>
                        </a>
                        <a href="exam_list.php" class="w-full">
                            <button class="w-full rounded-md border border-blue-600 bg-blue-600 px-8 py-4 text-lg font-medium text-white transition hover:bg-transparent hover:text-blue-600 focus:outline-none focus:ring active:text-blue-500">
                                Take an Exam
                            </button>
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </section>
</body>
</html>
