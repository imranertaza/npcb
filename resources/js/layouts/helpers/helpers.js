import { computed } from "vue";

export function truncateText(text = "", maxLength = 100, suffix = "...") {
    if (typeof text !== "string") return "";
    return text.length > maxLength ? text.slice(0, maxLength) + suffix : text;
}

export function getImageUrl(path) {
    if (!path) {
        return "/assets/images/default.svg"; // fallback image
    }

    return path.startsWith("http://") || path.startsWith("https://")
        ? path
        : `/storage/${path.replace(/^\/+/, "")}`;
}

export const generateSlug = (title) => {
    return title
        .toLowerCase()
        .replace(title, title)
        .replace(/^-+|-+$/g, "")
        .replace(/\s/g, "-")
        .replace(/\-\-+/g, "-");
};

export const getImageCacheUrl = (filePath, width = 200, height = 200, format = "jpg") => {
    const baseUrl = import.meta.env.VITE_APP_URL;
    const relativePath = getImageUrl(filePath);

    if (relativePath.startsWith("http://") || relativePath.startsWith("https://")) {
        return relativePath;
    }

    return `${baseUrl}/image/${width}/${height}/${format}/${relativePath.replace(/^\/+/, "")}`;
};

