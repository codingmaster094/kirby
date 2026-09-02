window.panel.plugin("my-company/live-preview", {
  sections: {
    "live-preview": {
      data() {
        return {
          label: "Preview",
          url: null,
          openUrl: null,
          key: 0
        };
      },
      created() {
        this.loadSection();
        this.$events.on("model.update", this.reload);
        this.$events.on("content.save", this.reload);
        this.$events.on("content.publish", this.reload);
      },
      destroyed() {
        this.$events.off("model.update", this.reload);
        this.$events.off("content.save", this.reload);
        this.$events.off("content.publish", this.reload);
      },
      methods: {
        loadSection() {
          const self = this;
          this.load().then(function (response) {
            self.label = response.label || "Preview";
            self.url = response.url;
            self.openUrl = response.openUrl || response.url;
          });
        },
        reload() {
          const self = this;
          this.load().then(function (response) {
            self.url = response.url;
            self.openUrl = response.openUrl || response.url;
            self.key += 1;
          });
        }
      },
      template: `
        <section class="k-live-preview-section">
          <header class="k-section-header">
            <k-headline>{{ label }}</k-headline>
            <k-button-group>
              <k-button
                icon="refresh"
                size="xs"
                variant="filled"
                @click="reload"
              >
                Refresh
              </k-button>
              <k-button
                v-if="openUrl"
                icon="open"
                size="xs"
                variant="filled"
                :link="openUrl"
                target="_blank"
              >
                Open
              </k-button>
            </k-button-group>
          </header>
          <div v-if="url" class="k-live-preview-frame">
            <iframe :key="key" :src="url" title="Page preview"></iframe>
          </div>
          <k-box v-else theme="info">
            Preview URL is not available for this page.
          </k-box>
        </section>
      `
    }
  }
});
