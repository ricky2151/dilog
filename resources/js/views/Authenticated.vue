<template>
    <fullscreen ref="fullscreen" @change="fullscreenChange">
        <v-app>
            <v-toolbar app dense fixed clipped-left color="red" dark>
                <v-toolbar-side-icon
                @click.stop="drawer = !drawer"
                ></v-toolbar-side-icon>
                <v-spacer></v-spacer>
                <v-tooltip bottom>
                    <template slot="activator">
                        <v-btn icon @click="toggleFullscreen">
                            <v-icon v-if="fullscreen == false">fullscreen</v-icon>
                            <v-icon v-else>fullscreen_exit</v-icon>
                        </v-btn>
                    </template>
                    <span v-if="fullscreen == false">Enter Fullscreen Mode</span>
                    <span v-else>Exit Fullscreen Mode</span>
                </v-tooltip>
                <v-menu open-on-hover offset-y offset-x>
                    <v-btn
                        class="text-none ma-0"
                        slot="activator"
                        depressed
                        flat
                        >
                        <span class="subheading text-xs-center overflow-text">
                            Hello,
                            <span class="font-weight-medium">
                                John Doe
                            </span>
                        </span>
                    </v-btn>

                    <v-list>
                        <v-list-tile
                            v-for="(item, index) in toolbarMenu"
                            :key="index"
                            :to="item.action"
                            >
                            <v-list-tile-action>
                                <v-icon>{{ item.icon }}</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                                <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list>
                </v-menu>
            </v-toolbar>

            <v-navigation-drawer
                v-model="drawer"
                app clipped fixed
            >
                <v-container fluid>
                    <v-img src="/assets/images/logo.png" contain class="my-5">
                    </v-img>
                </v-container>
                <!-- <v-list>
                    <v-list-tile
                        v-for="(item, index) in routes"
                        router
                        :to="item.route"
                        :key="'menu'+index"
                        >
                        <v-list-tile-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-tile-action>

                        <v-list-tile-content>
                            <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list> -->

                <v-list>
                    <!--  -->
                    <div v-for="(item,index) in routes">


                        <v-list-group
                            
                            v-if="item.subroutes"
                            router
                            :key="'menu'+index"
                            v-model="item.active"
                            
                            no-action
                            >
                            <template v-slot:activator>
                                <v-list-tile>
                                    <v-list-tile-action>
                                        <v-icon>{{ item.icon }}</v-icon>
                                    </v-list-tile-action>
                                    <v-list-tile-content>
                                        <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                            </template>

                            <v-list-tile

                                v-for="subItem in item.subroutes"
                                :key="subItem.subtitle"
                                :to="subItem.subaction"

                                @click=""
                            >
                                <v-list-tile-action>
                                    <v-icon>{{ subItem.subicon }}</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-content>
                                    <v-list-tile-title>{{ subItem.subtitle }}</v-list-tile-title>
                                </v-list-tile-content>

                                  
                            </v-list-tile>
                        </v-list-group>


                        <v-list-tile
                            v-else
                            router
                            :to="item.action"
                            :key="'menu'+index"
                            >
                            <v-list-tile-action>
                                <v-icon>{{ item.icon }}</v-icon>
                            </v-list-tile-action>

                            <v-list-tile-content>
                                <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>

                    </div>
                </v-list>
            </v-navigation-drawer>

            <v-content>
                <router-view></router-view>
            </v-content>
        </v-app>
    </fullscreen>
</template>

<script>

export default {
    data() {
        return {
            fullscreen: false,
            user: null,
            drawer: true,
            routes: [
                {
                    icon: "dashboard",
                    title: "Dashboard",
                    
                },
                {
                    icon: "store",
                    title: "Master Data",
                    subroutes:[
                    {
                        subicon:"category",
                        subtitle:"Goods Category",
                        subaction: "/category"
                    },
                    {
                        subicon:"dns",
                        subtitle:"Goods Unit",
                        subaction: "/unit"
                    },
                    {
                        subicon:"build",
                        subtitle:"Goods Attribute",
                        subaction: "/attribute"
                    },

                    {
                        subicon:"bookmarks",
                        subtitle:"COGS Type",
                        subaction: "/type"
                    },
                    {
                        subicon:"compare_arrows",
                        subtitle:"Price Category",
                        subaction: "/categorypriceselling"
                    },

                    {
                        subicon:"description",
                        subtitle:"Batch Source",
                        subaction: "/source"
                    },
                    
                    ]
                },
                {
                    icon: "widgets",
                    title : "Goods",
                    action : "/goods",
                },
                {
                    icon: "store",
                    title: "Warehouse",
                    action: "/warehouse",
                },
                {
                    icon: "store",
                    title : "Goods",
                    route : "/goods",
                }
               
            ],
            toolbarMenu: [
                {
                    icon: "account_circle",
                    title: "Account",
                    action: "/account"
                },
                {
                    icon: "contact_support",
                    title: "Support",
                    action: "/support"
                },
                {
                    icon: "feedback",
                    title: "Feedback",
                    action: "/feedback"
                },
                {
                    icon: "exit_to_app",
                    title: "Logout",
                    action: "/logout"
                }
            ]
        }
    },
    methods: {
        toggleFullscreen () {
            this.$refs['fullscreen'].toggle()
        },
        fullscreenChange(fullscreen) {
            this.fullscreen = fullscreen
        }
    }
}
</script>
