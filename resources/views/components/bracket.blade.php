<div x-data="bracket(@json($teams))" class="bg-[#082033] text-white p-6 rounded-lg shadow-lg max-w-6xl mx-auto">
  <div class="flex items-start gap-6">
    <!-- Quarterfinals (4 matches) -->
    <div class="w-1/3">
      <h3 class="font-bold text-lg mb-4">Kwartfinales</h3>
      <div class="space-y-6">
        <template x-for="(m, i) in quarterMatches" :key="i">
          <div class="match bg-[#073044] p-3 rounded-md relative">
            <div class="flex justify-between">
              <div class="w-1/2">
                <div class="match-team px-3 py-2 rounded cursor-pointer"
                     :class="{'bg-green-500 text-white': m.winner === 1}"
                     @click="selectWinner('quarter', i, 1)" x-text="m.team1"></div>
              </div>
              <div class="w-1/2 text-right">
                <div class="match-team px-3 py-2 rounded cursor-pointer"
                     :class="{'bg-green-500 text-white': m.winner === 2}"
                     @click="selectWinner('quarter', i, 2)" x-text="m.team2"></div>
              </div>
            </div>
            <!-- connector to semifinal -->
            <div class="connector-horizontal" :style="{top: '50%'}"></div>
          </div>
        </template>
      </div>
    </div>

    <!-- Semifinals (2 matches) -->
    <div class="w-1/3">
      <h3 class="font-bold text-lg mb-4">Halve finales</h3>
      <div class="space-y-12 mt-6">
        <template x-for="(m, i) in semiMatches" :key="i">
          <div class="match bg-[#073044] p-3 rounded-md relative h-24">
            <div class="flex justify-between items-center h-full">
              <div class="w-1/2">
                <div class="match-team px-3 py-2 rounded cursor-pointer"
                     :class="{'bg-green-500 text-white': m.winner === 1}"
                     @click="selectWinner('semi', i, 1)" x-text="m.team1 ?? '—'"></div>
              </div>
              <div class="w-1/2 text-right">
                <div class="match-team px-3 py-2 rounded cursor-pointer"
                     :class="{'bg-green-500 text-white': m.winner === 2}"
                     @click="selectWinner('semi', i, 2)" x-text="m.team2 ?? '—'"></div>
              </div>
            </div>
            <div class="connector-horizontal" :style="{top: '50%'}"></div>
          </div>
        </template>
      </div>
    </div>

    <!-- Final -->
    <div class="w-1/3">
      <h3 class="font-bold text-lg mb-4">Finale</h3>
      <div class="mt-12">
        <div class="match bg-[#073044] p-4 rounded-md h-32 flex flex-col justify-center items-center">
          <div class="w-full flex justify-between items-center mb-3">
            <div class="w-1/2">
              <div class="match-team px-3 py-2 rounded cursor-pointer text-left"
                   :class="{'bg-green-500 text-white': finalMatch.winner === 1}"
                   @click="selectWinner('final', 0, 1)" x-text="finalMatch.team1 ?? '—'"></div>
            </div>
            <div class="w-1/2 text-right">
              <div class="match-team px-3 py-2 rounded cursor-pointer"
                   :class="{'bg-green-500 text-white': finalMatch.winner === 2}"
                   @click="selectWinner('final', 0, 2)" x-text="finalMatch.team2 ?? '—'"></div>
            </div>
          </div>

          <div class="w-full text-center mt-2">
            <template x-if="champion">
              <div class="inline-block bg-yellow-400 text-black px-3 py-1 rounded font-semibold">Kampioen: <span x-text="champion"></span></div>
            </template>
            <template x-if="!champion">
              <div class="text-gray-300">Kies eerst winnaars</div>
            </template>
          </div>
        </div>

        <div class="mt-4 flex gap-2">
          <button @click="reset()" class="px-3 py-2 bg-red-500 rounded">Reset</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    function bracket(initialTeams) {
      return {
        quarterMatches: (function(){
          let arr = [];
          for (let i=0;i<initialTeams.length;i+=2) {
            arr.push({team1: initialTeams[i] ?? '—', team2: initialTeams[i+1] ?? '—', winner: null});
          }
          return arr;
        })(),
        semiMatches: [ {team1:null, team2:null, winner:null}, {team1:null, team2:null, winner:null} ],
        finalMatch: { team1:null, team2:null, winner:null },
        champion: null,
        selectWinner(round, index, playerIndex){
          if(round === 'quarter'){
            let m = this.quarterMatches[index];
            m.winner = playerIndex;
            let winner = playerIndex === 1 ? m.team1 : m.team2;
            let semiIndex = Math.floor(index/2);
            if (index % 2 === 0) this.semiMatches[semiIndex].team1 = winner; else this.semiMatches[semiIndex].team2 = winner;
            // reset downstream winners
            this.semiMatches[semiIndex].winner = null;
            this.finalMatch.team1 = null; this.finalMatch.team2 = null; this.finalMatch.winner = null; this.champion = null;
          } else if(round === 'semi'){
            let m = this.semiMatches[index];
            // only allow selecting if team exists
            if(!m.team1 && !m.team2) return;
            m.winner = playerIndex;
            let winner = playerIndex === 1 ? m.team1 : m.team2;
            this.finalMatch[ index === 0 ? 'team1' : 'team2'] = winner;
            this.finalMatch.winner = null; this.champion = null;
          } else if(round === 'final'){
            let m = this.finalMatch;
            if(!m.team1 && !m.team2) return;
            m.winner = playerIndex;
            this.champion = playerIndex === 1 ? m.team1 : m.team2;
          }
        },
        reset(){
          this.quarterMatches.forEach(m=>m.winner=null);
          this.semiMatches.forEach(m=>{m.team1=null; m.team2=null; m.winner=null;});
          this.finalMatch = {team1:null, team2:null, winner:null};
          this.champion = null;
        }
      }
    }
  </script>

  <style>
    .connector-horizontal{
      position:absolute;
      right:-1.5rem;
      height:2px;
      background:white;
      width:1.5rem;
      transform: translateY(-50%);
    }
    .match-team{ transition: all .15s ease; }
    .match-team:hover{ transform: translateY(-2px); }
  </style>
</div>
