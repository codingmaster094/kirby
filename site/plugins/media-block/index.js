window.panel.plugin("my-company/media-block", {
  components: {
    "k-media-hub-view": {
      props: {
        folders: {
          type: Array,
          default() {
            return [];
          }
        },
        files: {
          type: Array,
          default() {
            return [];
          }
        },
        tags: {
          type: Array,
          default() {
            return [];
          }
        },
        stats: {
          type: Object,
          default() {
            return {
              total: 0,
              unused: 0,
              folders: 0,
              images: 0,
              videos: 0
            };
          }
        }
      },
      data() {
        return {
          folder: "all",
          type: "all",
          tag: null,
          q: "",
          smart: null,
          dragging: false
        };
      },
      computed: {
        uploadUrl() {
          if (this.folder === "all") {
            return (this.folders[0] && this.folders[0].upload) || "site/files";
          }

          var match = this.folders.find(
            function (folder) {
              return folder.id === this.folder;
            }.bind(this)
          );

          return (match && match.upload) || "site/files";
        },
        filtered() {
          var query = (this.q || "").trim().toLowerCase();
          var folder = this.folder;
          var type = this.type;
          var tag = this.tag;
          var smart = this.smart;

          return (this.files || []).filter(function (file) {
            if (folder !== "all" && file.folder !== folder) {
              return false;
            }
            if (type !== "all" && file.type !== type) {
              return false;
            }
            if (tag && (file.tags || []).indexOf(tag) === -1) {
              return false;
            }
            if (smart === "unused" && file.used) {
              return false;
            }
            if (smart === "duplicates" && !file.duplicate) {
              return false;
            }
            if (
              query &&
              String(file.filename || "")
                .toLowerCase()
                .indexOf(query) === -1
            ) {
              return false;
            }
            return true;
          });
        },
        allCount() {
          return (this.files || []).length;
        }
      },
      methods: {
        thumb(file) {
          if (!file || !file.image) {
            return null;
          }
          return file.image.url || file.image.src || null;
        },
        pick() {
          var panel = this.$panel;
          panel.upload.pick({
            url: panel.urls.api + "/" + this.uploadUrl,
            multiple: true,
            on: {
              complete: function () {
                panel.view.refresh();
              }
            }
          });
        },
        onDrop(event) {
          this.dragging = false;
          var files = event.dataTransfer && event.dataTransfer.files;
          if (!files || !files.length) {
            return;
          }

          var panel = this.$panel;
          panel.upload.open(files, {
            url: panel.urls.api + "/" + this.uploadUrl,
            multiple: true,
            on: {
              complete: function () {
                panel.view.refresh();
              }
            }
          });
        },
        setFolder(id) {
          this.folder = id;
        },
        setType(type) {
          this.type = type;
        },
        setTag(name) {
          this.tag = this.tag === name ? null : name;
        },
        setSmart(name) {
          this.smart = this.smart === name ? null : name;
        },
        setQuery(value) {
          this.q = value;
        },
        deleteFile(file, event) {
          if (event) {
            event.preventDefault();
            event.stopPropagation();
          }

          if (!file || !file.api || file.canDelete === false) {
            return;
          }

          if (!window.confirm('Delete "' + file.filename + '"?')) {
            return;
          }

          var panel = this.$panel;

          this.$api
            .delete(file.api)
            .then(function () {
              panel.notification.success("Deleted");
              panel.view.refresh();
            })
            .catch(function (error) {
              panel.notification.error(
                (error && error.message) || "Delete failed"
              );
            });
        }
      },
      template: `
        <k-panel-inside class="k-media-hub-view">
          <k-header>
            Media Hub
            <k-button
              slot="buttons"
              icon="upload"
              variant="filled"
              theme="notice"
              @click="pick"
            >
              Upload
            </k-button>
          </k-header>

          <div class="k-media-hub">
            <aside class="k-media-hub-nav">
              <p class="k-media-hub-label">Folders</p>
              <button
                type="button"
                class="k-media-hub-folder"
                :aria-current="folder === 'all' ? 'true' : null"
                @click="setFolder('all')"
              >
                <span>All Files</span>
                <em>{{ allCount }}</em>
              </button>
              <button
                v-for="item in folders"
                :key="item.id"
                type="button"
                class="k-media-hub-folder"
                :aria-current="folder === item.id ? 'true' : null"
                @click="setFolder(item.id)"
              >
                <span>{{ item.label }}</span>
                <em>{{ item.count }}</em>
              </button>

              <p class="k-media-hub-label">Tags</p>
              <p v-if="!tags.length" class="k-media-hub-empty">No tags yet</p>
              <button
                v-for="item in tags"
                :key="'tag-' + item.name"
                type="button"
                class="k-media-hub-folder"
                :aria-current="tag === item.name ? 'true' : null"
                @click="setTag(item.name)"
              >
                <span>{{ item.name }}</span>
                <em>{{ item.count }}</em>
              </button>

              <p class="k-media-hub-label">Smart filter</p>
              <button
                type="button"
                class="k-media-hub-folder"
                :aria-current="smart === 'unused' ? 'true' : null"
                @click="setSmart('unused')"
              >
                Unused
              </button>
              <button
                type="button"
                class="k-media-hub-folder"
                :aria-current="smart === 'duplicates' ? 'true' : null"
                @click="setSmart('duplicates')"
              >
                Duplicates
              </button>
            </aside>

            <section class="k-media-hub-main">
              <div class="k-media-hub-stats">
                <span><strong>{{ stats.total }}</strong> Total files</span>
                <button type="button" @click="setSmart('unused')">
                  Check unused ({{ stats.unused }})
                </button>
                <span><strong>{{ stats.folders }}</strong> Folders</span>
                <span><strong>{{ stats.images }}</strong> Images</span>
                <span><strong>{{ stats.videos }}</strong> Videos</span>
              </div>

              <div class="k-media-hub-toolbar">
                <div class="k-button-group">
                  <k-button
                    :variant="type === 'all' ? 'filled' : 'dimmed'"
                    @click="setType('all')"
                  >
                    All
                  </k-button>
                  <k-button
                    :variant="type === 'image' ? 'filled' : 'dimmed'"
                    @click="setType('image')"
                  >
                    Images
                  </k-button>
                  <k-button
                    :variant="type === 'document' ? 'filled' : 'dimmed'"
                    @click="setType('document')"
                  >
                    Documents
                  </k-button>
                  <k-button
                    :variant="type === 'video' ? 'filled' : 'dimmed'"
                    @click="setType('video')"
                  >
                    Videos
                  </k-button>
                  <k-button
                    :variant="type === 'audio' ? 'filled' : 'dimmed'"
                    @click="setType('audio')"
                  >
                    Audio
                  </k-button>
                </div>
                <input
                  class="k-media-hub-search"
                  :value="q"
                  type="search"
                  placeholder="Search files"
                  @input="setQuery($event.target.value)"
                >
              </div>

              <div
                class="k-media-hub-drop"
                :data-over="dragging ? 'true' : null"
                @dragenter.prevent="dragging = true"
                @dragover.prevent="dragging = true"
                @dragleave.prevent="dragging = false"
                @drop.prevent="onDrop"
                @click="pick"
              >
                Drop files here or click to upload
              </div>

              <div v-if="filtered.length" class="k-media-hub-grid">
                <div
                  v-for="file in filtered"
                  :key="file.id"
                  class="k-media-hub-card"
                >
                  <a class="k-media-hub-card-link" :href="file.link">
                    <span class="k-media-hub-thumb">
                      <img
                        v-if="thumb(file)"
                        :src="thumb(file)"
                        :alt="file.filename"
                      >
                      <span v-else>{{ file.extension }}</span>
                    </span>
                    <strong>{{ file.filename }}</strong>
                    <small>{{ file.size }} · {{ file.extension }}</small>
                  </a>
                  <k-button
                    v-if="file.canDelete !== false"
                    class="k-media-hub-delete"
                    icon="trash"
                    size="xs"
                    variant="filled"
                    theme="negative"
                    :title="'Delete ' + file.filename"
                    @click="deleteFile(file, $event)"
                  >
                    Delete
                  </k-button>
                </div>
              </div>
              <p v-else class="k-media-hub-empty">No files match this filter</p>
            </section>
          </div>
        </k-panel-inside>
      `
    }
  }
});
