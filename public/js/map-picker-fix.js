// MapPicker Form Restoration Fix
// Mengatasi error "Cannot read properties of null (reading 'value')"

document.addEventListener("DOMContentLoaded", function () {
    // Override form restoration methods untuk mencegah error
    if (window.mapPicker) {
        const originalGetFormRestorationState =
            window.mapPicker.getFormRestorationState;
        const originalSetFormRestorationState =
            window.mapPicker.setFormRestorationState;

        window.mapPicker.getFormRestorationState = function () {
            try {
                if (
                    this.formRestorationHiddenInput &&
                    this.formRestorationHiddenInput.value
                ) {
                    return JSON.parse(this.formRestorationHiddenInput.value);
                }
                return false;
            } catch (error) {
                console.warn("MapPicker getFormRestorationState error:", error);
                return false;
            }
        };

        window.mapPicker.setFormRestorationState = function (state, zoom) {
            try {
                if (this.formRestorationHiddenInput) {
                    const data =
                        state ||
                        this.getFormRestorationState() ||
                        this.getCoordinates();
                    if (this.map && zoom !== undefined) {
                        data.zoom = zoom;
                    } else if (this.map) {
                        data.zoom = this.map.getZoom();
                    }
                    this.formRestorationHiddenInput.value =
                        JSON.stringify(data);
                }
            } catch (error) {
                console.warn("MapPicker setFormRestorationState error:", error);
            }
        };
    }

    // Fix untuk Alpine.js component
    if (window.Alpine) {
        window.Alpine.data("mapPicker", function (wire, config) {
            return {
                wire: wire,
                config: config,
                map: null,
                marker: null,
                formRestorationHiddenInput: null,

                init() {
                    this.formRestorationHiddenInput = document.getElementById(
                        this.$el.id + "_fmrest"
                    );
                    if (!this.formRestorationHiddenInput) {
                        this.formRestorationHiddenInput =
                            document.createElement("input");
                        this.formRestorationHiddenInput.type = "text";
                        this.formRestorationHiddenInput.id =
                            this.$el.id + "_fmrest";
                        this.formRestorationHiddenInput.style.display = "none";
                        this.formRestorationHiddenInput.value = "";
                        this.$el.appendChild(this.formRestorationHiddenInput);
                    }
                },

                getFormRestorationState() {
                    try {
                        if (
                            this.formRestorationHiddenInput &&
                            this.formRestorationHiddenInput.value
                        ) {
                            return JSON.parse(
                                this.formRestorationHiddenInput.value
                            );
                        }
                        return false;
                    } catch (error) {
                        console.warn(
                            "MapPicker getFormRestorationState error:",
                            error
                        );
                        return false;
                    }
                },

                setFormRestorationState(state, zoom) {
                    try {
                        if (this.formRestorationHiddenInput) {
                            const data =
                                state ||
                                this.getFormRestorationState() ||
                                this.getCoordinates();
                            if (this.map && zoom !== undefined) {
                                data.zoom = zoom;
                            } else if (this.map) {
                                data.zoom = this.map.getZoom();
                            }
                            this.formRestorationHiddenInput.value =
                                JSON.stringify(data);
                        }
                    } catch (error) {
                        console.warn(
                            "MapPicker setFormRestorationState error:",
                            error
                        );
                    }
                },
            };
        });
    }
});
