set :stage, :staging
server 'www.projektai.nfqakademija.lt:22129', user: 'fairytale', roles: %w{web}

set :deploy_to, '/home/fairytale/public_html/'
set :tmp_dir, '/tmp/fairytale'