import { defineAsyncComponent } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import { Draggable } from "@he-tree/vue";

const Master = defineAsyncComponent(() =>
    import("@/layouts/frontend/Master.vue")
);
const AppLayout = defineAsyncComponent(() =>
    import("@/layouts/AppLayout.vue")
);
const TextInput = defineAsyncComponent(() =>
    import("@/components/Forms/TextInput.vue")
);

const AcitJazzUpload = defineAsyncComponent(() =>
    import("@/components/Forms/AcitJazzUpload.vue")
);
const TinyEditor = defineAsyncComponent(() =>
    import("@/components/Forms/TinyEditor.vue")
);

const InputSlug = defineAsyncComponent(() =>
    import("@/components/Forms/InputSlug.vue")
);

const InputSelect = defineAsyncComponent(() =>
    import("@/components/Forms/InputSelect.vue")
);
const InputLabel = defineAsyncComponent(() =>
    import("@/components/Forms/InputLabel.vue")
);
const InputError = defineAsyncComponent(() =>
    import("@/components/Forms/InputError.vue")
);
const TextAreaInput = defineAsyncComponent(() =>
    import("@/components/Forms/TextAreaInput.vue")
);
const InputColor = defineAsyncComponent(() =>
    import("@/components/Forms/InputColor.vue")
);
const InputGallery = defineAsyncComponent(() =>
    import("@/components/Forms/InputGallery.vue")
);
const SelectSection = defineAsyncComponent(() =>
    import("@/components/Forms/SelectSection.vue")
);
const InputBanners = defineAsyncComponent(() =>
    import("@/components/Forms/InputBanners.vue")
);
const Logo = defineAsyncComponent(() =>
    import("@/components/Sections/Logo.vue")
);
const Th = defineAsyncComponent(() =>
    import("@/components/Table/Th.vue")
);
const Td = defineAsyncComponent(() =>
    import("@/components/Table/Td.vue")
);
const SecondaryLink = defineAsyncComponent(() =>
    import("@/components/Buttons/SecondaryLink.vue")
);
const SecondaryButton = defineAsyncComponent(() =>
    import("@/components/Buttons/SecondaryButton.vue")
);
const PrimaryButton = defineAsyncComponent(() =>
    import("@/components/Buttons/PrimaryButton.vue")
);
const APrimaryButton = defineAsyncComponent(() =>
    import("@/components/Buttons/APrimaryButton.vue")
);
const AOutlineButton = defineAsyncComponent(() =>
    import("@/components/Buttons/AOutlineButton.vue")
);
const Badge = defineAsyncComponent(() =>
    import("@/components/Buttons/Badge.vue")
);
const Pagination = defineAsyncComponent(() =>
    import("@/components/Buttons/Pagination.vue")
);
const SpinnerLoader = defineAsyncComponent(() =>
    import("@/components/Loaders/SpinnerLoader.vue")
);
export default {
    install(Vue) {
        Vue.component("Master", Master),
        Vue.component("AppLayout", AppLayout),
        Vue.component("Draggable", Draggable),
        Vue.component("Logo", Logo),
        Vue.component("TextInput", TextInput),
        Vue.component("AcitJazzUpload", AcitJazzUpload),
        Vue.component("TinyEditor", TinyEditor),
        Vue.component("InputSlug", InputSlug),
        Vue.component("InputSelect", InputSelect),
        Vue.component("InputLabel", InputLabel),
        Vue.component("InputError", InputError),
        Vue.component("TextAreaInput", TextAreaInput),
        Vue.component("InputBanners", InputBanners),
        Vue.component("InputColor", InputColor),
        Vue.component("InputGallery", InputGallery),
        Vue.component("SelectSection", SelectSection),
        Vue.component("Th", Th),
        Vue.component("Td", Td),
        Vue.component("SecondaryLink", SecondaryLink),
        Vue.component("PrimaryButton", PrimaryButton),
        Vue.component("SecondaryButton", SecondaryButton),
        Vue.component("APrimaryButton", APrimaryButton),
        Vue.component("AOutlineButton", AOutlineButton),
        Vue.component("Badge", Badge),
        Vue.component("Pagination", Pagination)
        Vue.component("SpinnerLoader", SpinnerLoader)
        Vue.component("Head", Head);
        Vue.component("Link", Link);
    },
};
