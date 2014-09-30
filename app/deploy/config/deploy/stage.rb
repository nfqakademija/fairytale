set :stage, :staging
set :password, ask('Server password:', nil)

server 'www.projektai.nfqakademija.lt:22129', user: 'fairytale', password: fetch(:password), roles: %w{web}

set :deploy_to, '/home/fairytale/public_html/'
set :tmp_dir, '/tmp/fairytale'
