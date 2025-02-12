/** @type {import('tailwindcss').Config} */
export default {
    mode: 'jit',
    content: [
        './resources/views/**/*.blade.php'
    ],
    theme: {
        extend: {},
    },
    darkMode: 'class',
    plugins: [],
    safelist: [
            "absolute",
            "top-10",
            "left-5",
            "h-full",
            "min-w-[300px]",
            "flex",
            "flex-col",
            "gap-1",
            "divide-indigo-50",
            "rounded-md",
            "bg-white",
            "p-0.5",
            "ring",
            "shadow-sm",
            "ring-slate-200",
            "gap-2",
            "bg-cyan-600",
            "px-2",
            "py-2",
            "text-white",
            "transition-all",
            "duration-200",
            "block",
            "text-amber-500",
            "text-sm",
            "font-semibold"
    ],
}

